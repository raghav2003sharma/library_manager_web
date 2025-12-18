const todayPage = document.getElementById("todayPage");
const todayPrev = document.getElementById("prevToday");
const todayNext = document.getElementById("nextToday");
const dashpageCount = document.getElementById("dashpage-count");

document.addEventListener("DOMContentLoaded", loadApprovedBorrow());

function loadApprovedBorrow(page=1) {

    fetch(`/app/controllers/admin/approved-borrow.php?page=${page}`)
        .then(res => res.json())
        .then(data => {
                todayPage.textContent = page;

            const container = document.getElementById("approved-list");
            container.innerHTML = ""; 

          

            data.records.forEach(req => {
                const card = document.createElement("div");
                card.classList.add("approved-card");

                card.innerHTML = `
                    <h3>${req.title}</h3>
                    <p>${req.author}</p>
                    <p><strong>Borrower:</strong> ${req.name} (${req.email})</p>
                    <p><strong>Borrow Date:</strong> ${req.borrow_date}</p>

                    <form method="POST" action="/app/controllers/admin/borrow-today.php">
                        <input type="hidden" name="user_id" value="${req.user_id}">
                        <input type="hidden" name="book_id" value="${req.book_id}">
                        <input type="hidden" name="borrow_date" value="${req.borrow_date}">
                        <button class="btn-primary">Borrow Now</button>
                    </form>
                `;

                container.appendChild(card);
            });
              const totalPages = Math.ceil(data.totalRows /data.limit);
             dashpageCount.textContent = totalPages;
             if(totalPages===1){
                document.getElementById("dash-pagination").style.display = "none";
             }

        if(page === 1){
        todayPrev.disabled = true;
                todayPrev.classList.add("disable");


    } else {
        todayPrev.disabled = false;
        todayPrev.classList.remove("disable");

    }
    if(page >= totalPages){
        todayNext.disabled = true;
        todayNext.classList.add("disable");

    } else {
        todayNext.disabled = false;
        todayNext.classList.remove("disable");

    }
      if (data.records.length === 0) {
                container.innerHTML = `<p class="empty">No approved requests today</p>`;
                return;
            }
        })
        .catch(err => {
            console.error("Error loading approved requests:", err);
        });
}
function borrowTodayPage(step){
    const currentPage = parseInt(todayPage.textContent);
    const newPage = currentPage + step;
    if(newPage < 1) return; 

    loadApprovedBorrow(newPage);
}
