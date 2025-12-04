const borrowTable = document.getElementById("borrowTable");
const borrowHistory = document.getElementById("borrowHistory");
const searchBorrow = document.getElementById("searchBorrow");
const searchHistory = document.getElementById("searchHistory");
 const prevBorrow = document.getElementById("prevBorrow");
const nextBorrow = document.getElementById("nextBorrow");
const prevPage = document.getElementById("prevPage");
const nextPage = document.getElementById("nextPage");
const returnEmailInput = document.getElementById("return-email");
const returnSuggestions = document.getElementById("return-suggestions");
const fineEmail = document.getElementById("fine-email");
const fineBook = document.getElementById("fine-book");
const fineSuggestions = document.getElementById("fine-suggestions");
const fineAmount = document.getElementById("fine-amount");
 if(searchBorrow){
    searchBorrow.addEventListener("keyup", () => {
        let query = searchBorrow.value.trim();
        loadBorrowRecords(query);
    });
}
if(searchHistory){
    searchHistory.addEventListener("keyup", () => {
        let query = searchHistory.value.trim();
        loadBorrowHistory(query);
    });
}
if(borrowTable){
    loadBorrowRecords();
}
function borrowPage(step){
    const pageNumber = document.getElementById("pageNumber");
    const currentPage = parseInt(pageNumber.textContent);
    const newPage = currentPage + step;
    if(newPage < 1) return; 
    pageNumber.textContent = newPage;

    loadBorrowRecords(searchBorrow.value.trim(), newPage);
}
    function loadBorrowRecords(query="",page=1){
    fetch(`../app/controllers/show-borrows.php?q=${query}&page=${page}`)
  .then(res => res.json())
  .then(data => {
      borrowTable.innerHTML = "";
    if (data.records.length === 0) {
                borrowTable.innerHTML = `
                    <tr>
                        <td colspan="5" style="text-align:center;padding:15px;">No records found.</td>
                    </tr>
                `;
                return;
            }
      data.records.forEach(book => {
          borrowTable.innerHTML += `
          <tr>
              <td>${book.name}</td>
              <td>${book.title}</td>
              <td>${book.status}</td>
              <td>${book.borrow_date}</td>
              <td>${book.due_date}</td>
          </tr>`;
      });
       const totalPages = Math.ceil(data.totalRows / 5);
        if(page === 1){
        prevBorrow.disabled = true;
    } else {
        prevBorrow.disabled = false;
    }
    if(page >= totalPages){
        nextBorrow.disabled = true;
    } else {
        nextBorrow.disabled = false;
    }
  });
}
if(borrowHistory){
    loadBorrowHistory();
}
function historyPage(step){
    const pageNumberSpan = document.getElementById("pageNumber");
    const currentPage = parseInt(pageNumberSpan.textContent);
    const newPage = currentPage + step;
    if(newPage < 1) return; 
    pageNumberSpan.textContent = newPage;

    loadBorrowHistory(searchHistory.value.trim(), newPage);
}
    function loadBorrowHistory(query="",page=1){
    fetch(`../app/controllers/borrow-history.php?q=${query}&page=${page}`)
  .then(res => res.json())
  .then(data => {
      borrowHistory.innerHTML = "";
 if (data.records.length === 0) {
                borrowHistory.innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align:center;padding:15px;">No records found.</td>
                    </tr>
                `;
                return;
            }

      data.records.forEach(book => {
          borrowHistory.innerHTML += `
          <tr>
              <td>${book.name}</td>
              <td>${book.title}</td>
              <td>${book.status}</td>
              <td>${book.borrow_date}</td>
              <td>${book.due_date}</td>
                <td>${book.return_date ? book.return_date : '-'}</td>
          </tr>`;
      });
        const totalPages = Math.ceil(data.totalRows / 5);
        if(page === 1){
        prevPage.disabled = true;
    } else {
        prevPage.disabled = false;
    }
    if(page >= totalPages){
        nextPage.disabled = true;
    } else {
        nextPage.disabled = false;
    }
  });
}
function fetchUserBooks(email){
    fetch(`../app/controllers/book-autosuggestions.php?email=${encodeURIComponent(email)}&type=borrow`)
        .then(res => res.json())
        .then(data => {
            const list = document.getElementById("book-suggestions");
            list.innerHTML = "";

            data.forEach(book => {
                const option = document.createElement("option");
                option.value = book.title;
                list.appendChild(option);
            });
        })
        .catch(err => console.error("Error fetching books:", err));

}
//autosuggestionfor borrow form
document.getElementById("borrow-email").addEventListener("blur", function() {
    const email = this.value.trim();
    if (email !== "") {
        fetchUserBooks(email);
    }
});

returnEmailInput.addEventListener("blur", function () {
    const email = this.value.trim();

    if (email.length < 3) {
        returnSuggestions.innerHTML = "";
        return;
    }

    fetch(`../app/controllers/book-autosuggestions.php?email=${encodeURIComponent(email)}&type=return`)
        .then(res => res.json())
        .then(data => {
            returnSuggestions.innerHTML = "";

            data.forEach(item => {
                const option = document.createElement("option");
                option.value = item.title;
                returnSuggestions.appendChild(option);

            });
        })
        .catch((err) => {
              console.error("Error fetching books:", err);
        });
})
let fineData = [];

// Load book suggestions for fine amount
fineEmail.addEventListener("blur", function () {
    const email = this.value.trim();

    if (email.length < 3) {
        fineSuggestions.innerHTML = "";
        return;
    }

    fetch(`../app/controllers/book-autosuggestions.php?email=${encodeURIComponent(email)}&type=fine`)
        .then(res => res.json())
        .then(data => {
               fineData = data; 
            fineSuggestions.innerHTML = "";

            data.forEach(item => {
                const option = document.createElement("option");
                option.value = item.title;
                fineSuggestions.appendChild(option);
            });
        })
        .catch(err => console.error("Error:", err));
})
fineBook.addEventListener("change", function () {
    const selectedTitle = this.value.trim();

    const book = fineData.find(b => b.title === selectedTitle);

    if (book) {
        fineAmount.value = book.fine_amount;
    } else {
        fineAmount.value = "";
    }
});