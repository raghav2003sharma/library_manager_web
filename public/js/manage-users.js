function showAddUserForm() {
    document.getElementById('addUserForm').style.display = 'block';
}
function hideAddUserForm() {
    document.getElementById('addUserForm').style.display = 'none';
}
const table = document.getElementById("userTableBody");
if(table){
fetch('../app/controllers/fetch-users.php')
  .then(res => res.json())
  .then(users => {
      
      table.innerHTML = "";

      users.forEach(user => {
          table.innerHTML += `
          <tr>
              <td>${user.user_id}</td>
              <td>${user.name}</td>
              <td>${user.email}</td>
              <td>${user.role}</td>
              <td>${user.created_at}</td>
                <td>
                        <button class="btn-edit" onclick="openEditModal('${user.user_id}','${user.name}','${user.email}','${user.role}')">Edit</button>
                        <button class="btn-delete" onclick="openDeleteModal('${user.user_id}')">Delete</button>
                    </td>
          </tr>`;
      });
  });
}
function openEditModal(id,username, email, role) {
    document.getElementById("editUsername").value = username;
    document.getElementById("editEmail").value = email;
    document.getElementById("editRole").value = role;
    document.getElementById("edit_id").value = id;
    document.getElementById("editUserForm").style.display = "block";
}

function closeEditModal() {
    document.getElementById("editUserForm").style.display = "none";
}
function openDeleteModal(id) {
    document.getElementById("delete_id").value = id;
    document.getElementById("deleteModal").style.display = "block";
}

function closeDeleteModal() {
    document.getElementById("deleteModal").style.display = "none";
}

