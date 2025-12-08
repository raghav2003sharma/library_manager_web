

userForm =document.querySelector(".add-user");
editUserForm = document.querySelector(".edit-user-form");
if(userForm){
 userForm.addEventListener("submit", function (e) {
          const username = document.querySelector("input[name='username']");
            const email = document.querySelector("input[name='email']");
            const password = document.querySelector("input[name='password']");
            const role = document.querySelector("select[name='role']");

        // 1. Check empty fields
        if (!username.value.trim() || !email.value.trim() || !password.value.trim() || !role.value.trim()) {
            alert("All fields are required.");
            e.preventDefault();
            return;
        }

        // 2. Username length < 3
        if (username.value.trim().length < 3) {
            alert("Username must be at least 3 characters long.");
            e.preventDefault();
            return;
        }
if (username.value.length > 30) {
    alert("Username too long.");
    e.preventDefault();
    return;
}
const usernamePattern = /^[A-Za-z]+$/;

if (!usernamePattern.test(username.value.trim())) {
    alert("Username can contain only letters.");
    e.preventDefault();
    return;
}
if (email.value.length > 50) {
    alert("Email too long.");
    e.preventDefault();
    return;
}
        // 3. Valid email
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(email.value.trim())) {
            alert("Please enter a valid email address.");
            e.preventDefault();
            return;
        }

        // 4. Password length >= 6
        if (password.value.length < 6) {
            alert("Password must be at least 6 characters long.");
            e.preventDefault();
            return;
        }

        
    });
}
if(editUserForm){
     editUserForm.addEventListener("submit", function (e) {

       const username = document.getElementById("editUsername");
    const email = document.getElementById("editEmail");
    const role = document.getElementById("editRole");

        // 1. Check empty fields
        if (!username.value.trim() || !email.value.trim()|| !role.value.trim()) {
            alert("All fields are required.");
            e.preventDefault();
            return;
        }

        // 2. Username length < 3
        if (username.value.trim().length < 3) {
            alert("Username must be at least 3 characters long.");
            e.preventDefault();
            return;
        }
if (username.value.length > 30) {
    alert("Username too long.");
    e.preventDefault();
    return;
}
const usernamePattern = /^[A-Za-z]+$/;

if (!usernamePattern.test(username.value.trim())) {
    alert("Username can contain only letters.");
    e.preventDefault();
    return;
}
if (email.value.length > 50) {
    alert("Email too long.");
    e.preventDefault();
    return;
}
        // 3. Valid email
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(email.value.trim())) {
            alert("Please enter a valid email address.");
            e.preventDefault();
            return;
        }

})
}
function openEditModal(id,username, email, role) {
        document.getElementById("editUserForm").style.display = "block";
    document.getElementById("editUsername").value = username;
    document.getElementById("editEmail").value = email;
    document.getElementById("editRole").value = role;
    document.getElementById("edit_id").value = id;
}
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
        prevBtn.classList.add("disable");

    } else {
        prevBtn.disabled = false;
                prevBtn.classList.remove("disable");

    }
    if(page >= totalPages){
        nextBtn.disabled = true;
        nextBtn.classList.add("disable");
    } else {
        nextBtn.disabled = false;
        nextBtn.classList.remove("disable");

    }
  });

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

