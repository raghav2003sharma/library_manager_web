
const finesTable = document.getElementById("fineTableBody");
const fineSearch = document.getElementById("fineSearch");
const prevFineBtn = document.getElementById("prevFineBtn");
const nextFineBtn = document.getElementById("nextFineBtn");

// Pagination State
let finePage = 1;

// Initial Load
if (finesTable) {
    loadFines();
}

// Search Trigger
if (fineSearch) {
    fineSearch.addEventListener("keyup", () => {
        finePage = 1;
        loadFines(fineSearch.value.trim(), finePage);
    });
}

// Pagination Handler
function changeFinePage(step) {
    const newPage = finePage + step;
    if (newPage < 1) return;

    finePage = newPage;
    loadFines(fineSearch.value.trim(), finePage);
}

// Fetch Fines
function loadFines(query = "", page = 1) {
    fetch(`../app/controllers/fetch-fines.php?q=${query}&page=${page}`)
        .then(res => res.json())
        .then(data => {
            finesTable.innerHTML = "";

            // No data
            if (data.fines.length === 0) {
                finesTable.innerHTML = `
                    <tr>
                        <td colspan="8" style="text-align:center;padding:15px;">No fines found.</td>
                    </tr>
                `;
                  // Pagination Logic
            const totalPages = Math.ceil(data.totalRows / 5);

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
                return;
            }

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

            document.getElementById("finePageNumber").textContent = page;
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


