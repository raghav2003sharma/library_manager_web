const borrowTable = document.getElementById("borrowTable");
const borrowHistory = document.getElementById("borrowHistory");
const searchBorrow = document.getElementById("searchBorrow");
const searchHistory = document.getElementById("searchHistory");
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
    function loadBorrowRecords(query=""){
    fetch(`../app/controllers/show-borrows.php?q=${query}`)
  .then(res => res.json())
  .then(books => {
      borrowTable.innerHTML = "";

      books.forEach(book => {
          borrowTable.innerHTML += `
          <tr>
              <td>${book.name}</td>
              <td>${book.title}</td>
              <td>${book.status}</td>
              <td>${book.borrow_date}</td>
              <td>${book.due_date}</td>
          </tr>`;
      });
  });
}
if(borrowHistory){
    loadBorrowHistory();
}
    function loadBorrowHistory(query=""){
    fetch(`../app/controllers/borrow-history.php?q=${query}`)
  .then(res => res.json())
  .then(books => {
      borrowHistory.innerHTML = "";

      books.forEach(book => {
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
  });
}