function showAddUserForm() {
    document.getElementById('addUserForm').style.display = 'block';
}
function hideAddUserForm() {
    document.getElementById('addUserForm').style.display = 'none';
}
const usersInput = document.getElementById("userSearch");
const table = document.getElementById("userTableBody");
const prevBtn = document.getElementById("prevBtn");
const nextBtn = document.getElementById("nextBtn");
if(table){
    loadUsers();
}
if(usersInput){
usersInput.addEventListener("keyup", () => {
    let query = usersInput.value.trim();
    loadUsers(query);
});
}
function stepChange(step){// on clicking next or previous
    const pageNumberSpan = document.getElementById("pageNumber");
    const currentPage = parseInt(pageNumberSpan.textContent);
    const newPage = currentPage + step;
    if(newPage < 1) return; 
    pageNumberSpan.textContent = newPage;
    
    loadUsers(usersInput.value.trim(), newPage);

}
function loadUsers(query="",page=1){
    console.log("page",page);
fetch(`../app/controllers/fetch-users.php?q=${query}&page=${page}`)
  .then(res => res.json())
  .then(data => {

      table.innerHTML = "";
       if (data.users.length === 0) {
                table.innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align:center;padding:15px;">No users found.</td>
                    </tr>
                `;
                return;
            }

      data.users.forEach(user => {
          table.innerHTML += `
          <tr>
              <td>${user.user_id}</td>
              <td>${user.name}</td>
              <td>${user.email}</td>
              <td>${user.role}</td>
              <td>${user.created_at}</td>
                <td>
                        <button class="btn-edit" onclick="openEditModal('${user.user_id}','${user.name}','${user.email}','${user.role}')"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn-delete" onclick="openDeleteModal('${user.user_id}')"><i class="fa-solid fa-trash"></i></button>
                    </td>
          </tr>`;
      });
        const totalPages = Math.ceil(data.totalRows / 5);
        if(page === 1){
        prevBtn.disabled = true;
    } else {
        prevBtn.disabled = false;
    }
    if(page >= totalPages){
        nextBtn.disabled = true;
    } else {
        nextBtn.disabled = false;
    }
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

