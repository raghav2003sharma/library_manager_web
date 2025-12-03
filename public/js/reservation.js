
const reserveSearch = document.getElementById("reserveSearch");
const reserveTable = document.getElementById("reserveTableBody");
const prevResBtn = document.getElementById("resPrevBtn");
const nextResBtn = document.getElementById("resNextBtn");

// Auto-load when table exists
if (reserveTable) {
    loadReservations();
}

// Search on keyup
if (reserveSearch) {
    reserveSearch.addEventListener("keyup", () => {
        let query = reserveSearch.value.trim();
        loadReservations(query);
    });
}

// Pagination step change
function resStepChange(step) {
    const pageSpan = document.getElementById("resPageNumber");
    const currentPage = parseInt(pageSpan.textContent);
    const newPage = currentPage + step;

    if (newPage < 1) return;

    pageSpan.textContent = newPage;
    loadReservations(reserveSearch.value.trim(), newPage);
}

function loadReservations(query = "", page = 1, filter = "") {
    if (!filter) {
        filter = document.querySelector(".filter-btn.active")?.dataset?.filter || "pending";
    }
    fetch(`../app/controllers/fetch-reserve.php?q=${query}&page=${page}&filter=${filter}`)
        .then(res => res.json())
        .then(data => {

            reserveTable.innerHTML = "";

            if (data.reservations.length === 0) {
                reserveTable.innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align:center;padding:15px;">No reservations found.</td>
                    </tr>
                `;
                return;
            }

            data.reservations.forEach(res => {
                reserveTable.innerHTML += `
                    <tr>
                        <td>${res.id}</td>
                        <td>${res.username}</td>
                        <td>${res.email}</td>
                        <td>${res.title}</td>
                        <td>
                            <img src="${res.cover_image}" style="width:50px;height:auto;">
                        </td>
                        <td>
                            ${
                                res.status === "pending"
                                ? `
                                    <button class="btn-edit" onclick="approveRequest(${res.id})">Approve</button>
                                    <button class="btn-delete" onclick="rejectRequest(${res.id})">Reject</button>
                                  `
                                : `No actions available`
                            }
                        </td>
                    </tr>
                `;
            });

            // Handle pagination
            const totalPages = Math.ceil(data.totalRows / 5);

            prevResBtn.disabled = page === 1;
            nextResBtn.disabled = page >= totalPages;
        })
        .catch(err => console.error("Fetch Error:", err));
}
function setFilter(btn) {
    document.querySelectorAll(".filter-btn").forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
    loadReservations(reserveSearch.value.trim(), 1, btn.dataset.filter);
}
function approveRequest(reservationId) {
    if (confirm("Are you sure you want to approve this reservation?")) {
        fetch('/app/controllers/approve-reserve.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: reservationId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Reservation approved successfully.");
                location.reload();
            } else {
                alert("Error approving reservation: " + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("An error occurred while processing the request.");
        });
    }
}

function rejectRequest(reservationId) {
    if (confirm("Are you sure you want to reject this reservation?")) {
        fetch('/app/controllers/reject-reserve.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: reservationId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Reservation rejected successfully.");
                location.reload();
            } else {
                alert("Error rejecting reservation: " + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("An error occurred while processing the request.");
        });
    }
}