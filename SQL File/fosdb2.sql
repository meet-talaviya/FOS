CREATE OR ALTER FUNCTION dbo.fn_CheckForPermission
(
    @UserId INT,
    @PermissionName NVARCHAR(100)
)
RETURNS BIT
AS
BEGIN
    DECLARE @result BIT = 0;

    IF EXISTS
    (
        SELECT 1
        FROM groups_users ug WITH (NOLOCK)
        INNER JOIN groups_roles gr WITH (NOLOCK)
            ON gr.group_id = ug.group_id
        INNER JOIN role_permission rp WITH (NOLOCK)
            ON rp.role_id = gr.role_id
        INNER JOIN permission p WITH (NOLOCK)
            ON p.id = rp.permission_id
        WHERE ug.user_id = @UserId
          AND p.name = @PermissionName
    )
    BEGIN
        SET @result = 1;
    END

    RETURN @result;
END;
GO