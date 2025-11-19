function showAddUserForm() {
    document.getElementById('addUserForm').style.display = 'block';
}
function hideAddUserForm() {
    document.getElementById('addUserForm').style.display = 'none';
}
document.getElementById('btn-add').addEventListener('click', showAddUserForm);

fetch('../app/controllers/fetch-users.php')
  .then(res => res.json())
  .then(users => {
      const table = document.getElementById("tableBody");
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
                        <button class="btn-edit" onclick="openEditModal('${user.name}','${user.email}','${user.role}')">Edit</button>
                        <button class="btn-delete">Delete</button>
                    </td>
          </tr>`;
      });
  });
function openEditModal(username, email, role) {
    document.getElementById("editUsername").value = username;
    document.getElementById("editEmail").value = email;
    document.getElementById("editRole").value = role;

    document.getElementById("editUserForm").style.display = "block";
}

function closeEditModal() {
    document.getElementById("editUserForm").style.display = "none";
}
