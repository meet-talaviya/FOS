CREATE OR ALTER PROCEDURE dbo.sp_InsertProjectTrackerMapping
    @ProjectTrackerType dbo.ProjectTrackerType READONLY
AS
BEGIN
    SET NOCOUNT ON;

    -- Validate mappings that would be deleted
    IF EXISTS
    (
        SELECT 1
        FROM dbo.projects_trackers pt
        INNER JOIN dbo.issues i
            ON i.project_id = pt.project_id
           AND i.tracker_id = pt.tracker_id
        WHERE pt.tracker_id IN
        (
            SELECT DISTINCT tracker_id
            FROM @ProjectTrackerType
        )
        AND NOT EXISTS
        (
            SELECT 1
            FROM @ProjectTrackerType source
            WHERE source.project_id = pt.project_id
              AND source.tracker_id = pt.tracker_id
        )
    )
    BEGIN
        SELECT
            -202 AS error,
            'This tracker cannot be unselected because it is already used in issues in the project.' AS error_message;

        RETURN;
    END;

    -- Continue with the existing mapping logic
    MERGE dbo.projects_trackers AS target
    USING @ProjectTrackerType AS source
        ON target.project_id = source.project_id
       AND target.tracker_id = source.tracker_id

    WHEN NOT MATCHED BY TARGET THEN
        INSERT (project_id, tracker_id)
        VALUES (source.project_id, source.tracker_id)

    WHEN NOT MATCHED BY SOURCE
         AND target.tracker_id IN
         (
             SELECT DISTINCT tracker_id
             FROM @ProjectTrackerType
         )
    THEN DELETE;

    SELECT
        0 AS error,
        'Project tracker mappings updated successfully.' AS error_message;
END;