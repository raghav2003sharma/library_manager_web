function loadApprovedBorrow() {

    fetch("../app/controllers/approved-borrow.php")
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById("approved-list");
            container.innerHTML = ""; 

            if (data.length === 0) {
                container.innerHTML = `<p class="empty">No approved requests today</p>`;
                return;
            }

            data.forEach(req => {
                const card = document.createElement("div");
                card.classList.add("approved-card");

                card.innerHTML = `
                    <h3>${req.title}</h3>
                    <p>${req.author}</p>
                    <p><strong>User:</strong> ${req.name} (${req.email})</p>
                    <p><strong>Borrow Date:</strong> ${req.borrow_date}</p>

                    <form method="POST" action="../app/controllers/borrow-today.php">
                        <input type="hidden" name="user_id" value="${req.user_id}">
                        <input type="hidden" name="book_id" value="${req.book_id}">
                        <input type="hidden" name="borrow_date" value="${req.borrow_date}">
                        <button class="btn-primary">Borrow Now</button>
                    </form>
                `;

                container.appendChild(card);
            });
        })
        .catch(err => {
            console.error("Error loading approved requests:", err);
        });
}

document.addEventListener("DOMContentLoaded", loadApprovedBorrow);
