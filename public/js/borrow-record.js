const borrowTable = document.getElementById("borrowTable");
if(borrowTable){
    fetch('../app/controllers/show-borrows.php')
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