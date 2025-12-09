SELECT * FROM USU_USUARIO

CREATE PROCEDURE AGENDARCITA
    @ClienteID INT,
    @ProfesionalID INT,
    @ServicioID INT,
    @FechaInicio DATETIME
AS
BEGIN
    SET NOCOUNT ON;

    -- 1. VARIABLES PARA CÁLCULOS
    DECLARE @Duracion INT;
    DECLARE @FechaFin DATETIME;
    DECLARE @Mensaje VARCHAR(100);

    -- 2. OBTENER LA DURACIÓN DEL SERVICIO
    -- Buscamos cuántos minutos dura el servicio seleccionado
    SELECT @Duracion = SER_DURACION_MIN 
    FROM SER_SERVICIO 
    WHERE SER_ID = @ServicioID;

    -- Validación: Si el servicio no existe
    IF @Duracion IS NULL
    BEGIN
        SELECT 'ERROR' AS Resultado, 'El servicio seleccionado no es válido.' AS Mensaje;
        RETURN;
    END

    -- 3. CALCULAR LA HORA DE FIN
    -- Sumamos los minutos de duración a la fecha de inicio
    SET @FechaFin = DATEADD(MINUTE, @Duracion, @FechaInicio);

    -- 4. VERIFICAR DISPONIBILIDAD (EVITAR CHOQUES)
    -- Revisamos si existe alguna cita para ESE profesional que se solape con el nuevo horario.
    -- (Ignoramos las citas canceladas)
    IF EXISTS (
        SELECT 1 FROM CIT_CITA
        WHERE CIT_USU_ID_PROFESIONAL = @ProfesionalID
          AND CIT_ESTADO IN ('Pendiente', 'Confirmada') -- Solo importan las activas
          AND (
                (@FechaInicio >= CIT_FECHA_HORA_INICIO AND @FechaInicio < CIT_FECHA_HORA_FIN) -- Empieza dentro de otra
                OR 
                (@FechaFin > CIT_FECHA_HORA_INICIO AND @FechaFin <= CIT_FECHA_HORA_FIN) -- Termina dentro de otra
                OR
                (@FechaInicio <= CIT_FECHA_HORA_INICIO AND @FechaFin >= CIT_FECHA_HORA_FIN) -- Envuelve a otra
              )
    )
    BEGIN
        -- Si entra aquí, es que está ocupado
        SELECT 'ERROR' AS Resultado, 'El profesional ya tiene una cita en ese horario.' AS Mensaje;
        RETURN;
    END

    -- 5. INSERTAR LA CITA
    -- Si pasó todas las validaciones, guardamos.
    BEGIN TRY
        INSERT INTO CIT_CITA 
        (CIT_CLI_ID, CIT_USU_ID_PROFESIONAL, CIT_SER_ID, CIT_FECHA_HORA_INICIO, CIT_FECHA_HORA_FIN, CIT_ESTADO)
        VALUES 
        (@ClienteID, @ProfesionalID, @ServicioID, @FechaInicio, @FechaFin, 'Pendiente');

        -- Devolvemos éxito
        SELECT 'EXITO' AS Resultado, 'Cita agendada correctamente.' AS Mensaje;
    END TRY
    BEGIN CATCH
        -- Si hay error de SQL (ej: base de datos caída)
        SELECT 'ERROR' AS Resultado, 'Error interno al guardar la cita.' AS Mensaje;
    END CATCH
END
 




select * from SER_SERVICIO
select * from CLI_CLIENTE
select * from USU_USUARIO
select * from ROL_ROL
select * from CIT_CITA

INSERT INTO ROL_ROL(ROL_ID,ROL_NOMBRE)
VALUES ('4', 'Cliente');




EXEC AGENDARCITA 
    @ClienteID = 1, 
    @ProfesionalID = 3, 
    @ServicioID = 2, 
    @FechaInicio = '2025-12-20 09:00:00';



EXEC AGENDARCITA
	@ClienteID=2,
	@ProfesionalID=2,
	@ServicioID=2,
	@FechaInicio='2025-12-22 09:00:00';