let currentPage = 1;
let isLoading = false;
let hasMore = true;
let currentCategory = "all";
let currentQuery = "";
window.onload = function () {
    const savedPage = localStorage.getItem("activePage") || "home";

    if (savedPage === "home") showAvailable();
    if (savedPage === "borrow") showBorrowed();
    if (savedPage === "settings") toggleSettings();
};
document.addEventListener("click", function (e) {
    const dropdown = document.getElementById("settingsDropdown");

    // if dropdown is visible AND clicked outside it
    if (dropdown.style.display === "block" && !dropdown.contains(e.target)) {
        dropdown.style.display = "none";
    }
});
function toggleSettings(e){
    e.stopPropagation();
    const dropdown = document.querySelector('.dropdown');
    if(dropdown.style.display === "none" || dropdown.style.display === ""){
        dropdown.style.display = "block";
    } else {
        dropdown.style.display = "none";
    }
}
fetchAvailableBooks();
fetchBorrowedBooks();
fetchReservations();
function clearSearch(){
     const search = document.getElementById("search-inp");
     const category = document.getElementById("allCategory");
     if(search.value === "") return;
    document.getElementById("suggestions").style.display = "none";
    search.value = "";
    isLoading = false;
    hasMore = true;
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    category.classList.add('active');
    fetchAvailableBooks();
}
function loadCategory(button,category){
    currentCategory = category;  
    currentPage = 1;             
    hasMore = true;             
    isLoading = false;
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    button.classList.add('active');
}


function fetchAvailableBooks(category="all",query="",page=1,append=false){
           if (isLoading || !hasMore) return;
            isLoading = true;
    fetch(`/app/controllers/fetch-available.php?category=${category}&q=${query}&page=${page}`)
    .then(response => response.json())
    .then(data => {
          const booksContainer = document.getElementById('avail');

            // Clear container only on the first page
            if (!append) {
                booksContainer.innerHTML = '';
                currentPage = 1;
                hasMore = true;
            }

            if (data.length === 0) {
                 hasMore = false;
                if (!append) {
                    booksContainer.innerHTML = `
                        <p class="no-books-msg">No books available in this category.</p>
                    `;
                }
                return;
            }
        // data.length === 0 && (booksContainer.innerHTML = '<p>No books available in this category.</p>');
        data.forEach(book => {
            const bookDiv = document.createElement('div');
            bookDiv.classList.add('book-card');
            bookDiv.innerHTML = `
                <a style="text-decoration:none;color:black;" href="/public/index.php?page=view-book&id=${book.book_id}"> 
                <img src="${book.cover_image}" />
                <div class="title">${book.title}</div>
            <div class="author">${book.author}</div>
            <div class="category-tag">${book.category}</div>    
            <div class="availability available">Available(${book.stock})</div>
                </a>
            `;
            booksContainer.appendChild(bookDiv);
        })
                    isLoading = false;

    })
}

window.addEventListener('scroll', () => {
    const bottom = window.innerHeight + window.scrollY >= document.body.offsetHeight - 500;

    if (bottom && !isLoading && hasMore) {
        currentPage++;
        fetchAvailableBooks(currentCategory,currentQuery, currentPage, true);
    }
});
document.getElementById('reservation-filter').addEventListener('change', function () {
    const filterValue = this.value;  

    fetchReservations(filterValue);
});
function fetchBorrowedBooks(){
    fetch('/app/controllers/fetch-borrowed.php',{
         
        method: "GET",
        credentials: "include"  
    })
    .then(response => response.json())
    .then(data => {
        const borrowedContainer = document.getElementById('borrow');
        borrowedContainer.innerHTML = '';
        if (!data || data.length === 0) {
            borrowedContainer.innerHTML = '<p>No borrowed records found.</p>';
            return;
        }
        data.forEach(book => {
            const bookDiv = document.createElement('div');
            bookDiv.classList.add('book-card');
            bookDiv.innerHTML = `
                <img src="${book.cover_image}" />
                <div class="title">${book.title}</div>
            <div class="author">${book.author}</div>
            <div class="category-tag">${book.category}</div>
            <div class="due-date">Borrow-date: ${book.borrow_date}</div>
              <div class="due-date">Due: ${book.due_date}</div>
            `;
            borrowedContainer.appendChild(bookDiv);
        })
    })
}

function fetchReservations(filter = 'pending', page = 1, search = ''){
     fetch(`/app/controllers/user-reservations.php?filter=${filter}&page=${page}&q=${encodeURIComponent(search)}`, {
        method: "GET",
        credentials: "include"
    })
    .then(response => response.json())
    .then(data => {
        const reservationSection = document.getElementById('reserves');
        reservationSection.innerHTML = ''; 

        if (!data.reservations || data.reservations.length === 0) {
            reservationSection.innerHTML = '<p>No reservations found.</p>';
            return;
        }

        data.reservations.forEach(res => {
            const bookDiv = document.createElement('div');
            bookDiv.classList.add('book-card');
            bookDiv.innerHTML = `
                <img src="${res.cover_image}" alt="${res.title}" />
                <div class="title">${res.title}</div>
                <div class="status">Status: ${res.status}</div>
                ${res.borrow_date ? `<div class="due-date">Borrow-date: ${res.borrow_date}</div>` : ''}
                <div class="due-date">Reserved at: ${res.reserved_at}</div>
                  <div class="card-actions">
               ${res.status ==="pending" ?` <button onclick="openEditDate(${res.book_id})"class="edit-btn"><i class="fa-solid fa-pen"></i></button>`:''}
                <button onclick="deleteReservation(${res.book_id},'${res.status}')"class="delete-btn"><i class="fa-solid fa-trash"></i></button>
                 </div>
                
            `;
            reservationSection.appendChild(bookDiv);
        });

    })
    .catch(err => {
        console.error(err);
    });


}
function deleteReservation(id,status){
    document.getElementById("reserve_delete_id").value = id;
    document.getElementById("reserve_status").value = status;
    document.getElementById("deleteReserveModal").style.display = "flex";
}
 function closeDeleteBook() {
        document.getElementById("deleteReserveModal").style.display = "none";
    }
function showForm(){
    const form = document.querySelector(".pass-modal");
    if(form.style.display === "none" || form.style.display === ""){
        form.style.display = "flex";
    } else {
        form.style.display = "none";
    }
}
function closeChangePass(){
    document.querySelector(".pass-modal").style.display = "none";
}
function searchBooks(){
    const query = document.getElementById("search-inp").value.trim();
      currentPage = 1;
    hasMore = true;
    isLoading = false;
        fetchAvailableBooks(currentCategory,query,currentPage,false);


}
function showAvailable() {
  document.getElementById("home-section").style.display = "block";
    document.getElementById("borrowed-section").style.display = "none";
     document.getElementById("about-us").style.display = "none";
    document.getElementById("contact").style.display = "none";
            // document.getElementById("reservation-section").style.display = "none";


}

function showBorrowed() {
    document.getElementById("home-section").style.display = "none";
    document.getElementById("borrowed-section").style.display = "block";
     document.getElementById("about-us").style.display = "none";
    document.getElementById("contact").style.display = "none";
            // document.getElementById("reservation-section").style.display = "none";


}
function showPage(page) {
    localStorage.setItem("activePage", page);

    if (page === "home") showAvailable();
    if (page === "borrow") showBorrowed();
    if(page === "about") {
         document.getElementById("home-section").style.display = "none";
    document.getElementById("borrowed-section").style.display = "none";
    document.getElementById("contact").style.display = "none";
    document.getElementById("about-us").style.display = "block";
            // document.getElementById("reservation-section").style.display = "none";


    }
    if(page==="contact"){
         document.getElementById("home-section").style.display = "none";
    document.getElementById("borrowed-section").style.display = "none";
    document.getElementById("about-us").style.display = "none";
    document.getElementById("contact").style.display = "block";
        // document.getElementById("reservation-section").style.display = "none";

    }
    if(page==="reservation"){
         document.getElementById("home-section").style.display = "none";
    document.getElementById("borrowed-section").style.display = "none";
    document.getElementById("about-us").style.display = "none";
    document.getElementById("contact").style.display = "none";
    // document.getElementById("reservation-section").style.display = "block";
    }
    if (page === "settings") toggleSettings();
   
   
}
function toggleMenu() {
    document.getElementById("navLinks").classList.toggle("active");
}
function autoSearch(query) {
    if (query.length < 1) {
        document.getElementById("suggestions").innerHTML = "";
        return;
    }

    fetch("/app/controllers/search-suggestions.php?q=" + encodeURIComponent(query))
        .then(res => res.json())
        .then(data => {
            let html = "";

            data.forEach(book => {
                html += `<div class="suggest-item" onclick="selectSuggestion('${book.title}')">
                            ${book.title}
                         </div>`;
            });

            document.getElementById("suggestions").innerHTML = html;
        });
}
function selectSuggestion(title) {
    document.getElementById("search-inp").value = title;
    document.getElementById("suggestions").innerHTML = "";
}

function openReserveForm() {
    document.getElementById("reserveModal").style.display = "flex";
}

function closeReserveForm() {
    document.getElementById("reserveModal").style.display = "none";
}

function confirmReserve() {
    return confirm("Are you sure you want to reserve this book?");
}
function openEditDate(id){
        document.getElementById("editDateModal").style.display = "flex";
        document.getElementById("hidden-bookid").value = id;

}
function closeDateForm(){
            document.getElementById("editDateModal").style.display = "none";

}
function showUser(email,name) {
    const modal = document.getElementById("editUserModal");
    document.getElementById("editProfileName").value = name;
    document.getElementById("editProfileEmail").value = email;

    modal.style.display = "flex";
}
function closeEditUser() {
    document.getElementById("editUserModal").style.display = "none";
}
function confirmDeleteUser() {
    if (confirm("Are you sure you want to delete your account? This cannot be undone.")) {
        window.location.href = "../app/controllers/delete-profile.php";
    }
}

// document.getElementById("contactForm").addEventListener("submit", function (e) {
//     e.preventDefault();

//     const formData = new FormData(this);

//     fetch("/app/controllers/contact-form.php", {
//         method: "POST",
//         body: formData,
//         credentials: "include"
//     })
//     .then(res => res.json())
//     .then(data => {
//         const msg = document.getElementById("contact-message");

//         if (data.success) {
            
//             msg.innerHTML = `<div style="color:green;" class="success">! ${data.message}</div>`;
//             this.reset(); // Clear form
            
//         } else {
//             msg.innerHTML = `<div style="color:red;" class="error">! ${data.message}</div>`;
//                     this.reset(); // Clear form

//         }
//           setTimeout(() => {
//             msg.innerHTML = "";
//     }, 3000);

//     });
// });