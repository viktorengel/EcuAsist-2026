<?php
$pageTitle = "Gestión de Usuarios - " . SITE_NAME;
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/nav.php';
?>

<div class="users-list">
    <h1>👥 Gestión de Usuarios</h1>
    
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Email</th>
                <th>Identificación</th>
                <th>Roles</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['identification']; ?></td>
                    <td>
                        <?php if ($user['roles']): ?>
                            <?php foreach (explode(',', $user['roles']) as $role): ?>
                                <span class="badge badge-role"><?php echo $role; ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="badge badge-secondary">Sin roles</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($user['is_active']): ?>
                            <span class="badge badge-success">Activo</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Inactivo</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button onclick="openRoleModal(<?php echo $user['id']; ?>, '<?php echo addslashes($user['first_name'] . ' ' . $user['last_name']); ?>')" 
                                class="btn btn-sm btn-secondary">
                            Asignar Rol
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="roleModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeRoleModal()">&times;</span>
        <h2>Asignar Rol</h2>
        <p id="modal-user-name"></p>
        
        <form method="POST" action="<?php echo BASE_URL; ?>index.php?page=assign-role">
            <input type="hidden" id="modal-user-id" name="user_id">
            
            <div class="form-group">
                <label for="role_id">Rol *</label>
                <select id="role_id" name="role_id" required>
                    <option value="">Seleccionar rol</option>
                    <?php
                    require_once __DIR__ . '/../../models/Role.php';
                    $roleModel = new Role();
                    $allRoles = $roleModel->getAll();
                    foreach ($allRoles as $role):
                    ?>
                        <option value="<?php echo $role['id']; ?>"><?php echo $role['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Asignar</button>
        </form>
    </div>
</div>

<script>
function openRoleModal(userId, userName) {
    document.getElementById('modal-user-id').value = userId;
    document.getElementById('modal-user-name').textContent = 'Usuario: ' + userName;
    document.getElementById('roleModal').style.display = 'block';
}

function closeRoleModal() {
    document.getElementById('roleModal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('roleModal');
    if (event.target == modal) {
        closeRoleModal();
    }
}
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
