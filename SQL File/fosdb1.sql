CREATE OR ALTER PROCEDURE dbo.sp_UpdateModuleStatus
    @Id NVARCHAR(MAX)
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @ErrorMessage NVARCHAR(MAX);

    -- Validate selected modules that are already enabled in projects
    SELECT @ErrorMessage = STRING_AGG(m.name, ', ')
    FROM dbo.modules m
    INNER JOIN
    (
        SELECT DISTINCT TRY_CAST(TRIM(value) AS INT) AS id
        FROM STRING_SPLIT(@Id, ',')
    ) s ON s.id = m.id
    WHERE m.status = 1
      AND EXISTS
      (
          SELECT 1
          FROM dbo.enabled_modules em
          WHERE em.name = m.value
      );

    -- Return error if any selected active module is in use
    IF @ErrorMessage IS NOT NULL
    BEGIN
        SELECT
            0 AS status,
            CONCAT(
                'Cannot update the following modules because they are enabled in projects: ',
                @ErrorMessage
            ) AS message;
        RETURN;
    END;

    -- Set all modules inactive
    UPDATE dbo.modules
    SET status = 0;

    -- Activate selected modules
    UPDATE m
    SET m.status = 1
    FROM dbo.modules m
    INNER JOIN
    (
        SELECT DISTINCT TRY_CAST(TRIM(value) AS INT) AS id
        FROM STRING_SPLIT(@Id, ',')
    ) s ON s.id = m.id
    WHERE s.id IS NOT NULL;

    SELECT
        1 AS status,
        'Module status updated successfully.' AS message;
END;