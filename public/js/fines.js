
const finesTable = document.getElementById("fineTableBody");
const fineSearch = document.getElementById("fineSearch");
const prevFineBtn = document.getElementById("prevFineBtn");
const nextFineBtn = document.getElementById("nextFineBtn");
const finepage =document.getElementById("finePageNumber");

let fineSort = "borrow_date";
let fineOrder = "asc";
document.querySelectorAll(".sortable").forEach(header => {
    header.addEventListener("click", () => {
        const column = header.dataset.column;
        const type = header.dataset.type;

        if (fineSort === column) {
            fineOrder = fineOrder === "asc" ? "desc" : "asc";
        } else {
            fineSort = column;
            fineOrder = "asc";
        }
      if(type==="fines"){
        finepage.textContent = "1";
        loadFines(fineSearch.value.trim(),1,fineSort,fineOrder);
      }
    });
});
// Initial Load
if (finesTable) {
    loadFines();
}

// Search Trigger
if (fineSearch) {
    fineSearch.addEventListener("keyup", () => {
        loadFines(fineSearch.value.trim());
    });
}

// Pagination Handler
function changeFinePage(step) {
      const currentPage = parseInt(finepage.textContent);
    const newPage = currentPage + step;
    if(newPage < 1) return; 

    loadFines(fineSearch.value.trim(), newPage);
}

// Fetch Fines
function loadFines(query = "", page = 1,sort="borrow_date",order="asc") {
    fetch(`/app/controllers/admin/fetch-fines.php?q=${query}&page=${page}&sort=${sort}&order=${order}`)
        .then(res => res.json())
        .then(data => {
                finepage.textContent = page;

            finesTable.innerHTML = "";

            
            // Render rows
            data.fines.forEach(fine => {
                finesTable.innerHTML += `
                    <tr>
                        <td>${fine.username}</td>
                        <td>${fine.email}</td>
                        <td>${fine.title}</td>
                        <td>${fine.author}</td>
                        <td>${fine.borrow_date}</td>
                        <td>${fine.return_date}</td>
                        <td>₹${fine.amount}</td>
                        <td>
                            <button class="btn-pay" 
                                onclick="openPayFineModal(
                                    '${fine.username}',
                                    '${fine.email}',
                                    '${fine.title.replace(/"/g, '&quot;')}',
                                    '${fine.amount}'
                                )">
                                Pay Fine
                            </button>
                        </td>
                    </tr>
                `;
            });

            // Pagination Logic
            const totalPages = Math.ceil(data.totalRows / 5);
             document.getElementById("finepage-count").textContent = totalPages;


            if (page === 1) {
                prevFineBtn.disabled = true;
                prevFineBtn.classList.add("disable");
            } else {
                prevFineBtn.disabled = false;
                prevFineBtn.classList.remove("disable");
            }

            if (page >= totalPages) {
                nextFineBtn.disabled = true;
                nextFineBtn.classList.add("disable");
            } else {
                nextFineBtn.disabled = false;
                nextFineBtn.classList.remove("disable");
            }

            if (data.fines.length === 0) {
                finesTable.innerHTML = `
                    <tr>
                        <td colspan="8" style="text-align:center;padding:15px;">No fines found.</td>
                    </tr>
                `;
            }

        });
}


function openPayFineModal( username, email, book, amount) {
    document.getElementById("fine_email").value = email;
        document.getElementById("fine_title").value = book;
    document.getElementById("fine_amount").value = amount;

    document.getElementById("fineUsername").textContent = username;
    document.getElementById("fineEmail").textContent = email;
    document.getElementById("fineBook").textContent = book;
    document.getElementById("fineAmount").textContent = amount;

    document.getElementById("payFineForm").style.display = "block";
}

function closePayFineModal() {
    document.getElementById("payFineForm").style.display = "none";
}


