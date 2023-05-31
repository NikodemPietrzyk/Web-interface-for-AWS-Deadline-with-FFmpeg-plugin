
var ajax = new XMLHttpRequest();
var method = "GET";

var table = document.getElementById('mytable');
var input = document.getElementById('myinput');
let container = document.getElementById('container');

var url = "functions/localPath.php\?path=";
var path = "";
var asynchronous = false;
var tableData = [];
getDirectories(path);

var fileList = JSON.parse(localStorage.getItem('files'));

if(JSON.parse(localStorage.getItem('files')) != null){
    showSourceButton(fileList.length);
}else{
//    showSourceButton(fileList.length);
}



function getDirectories(path) {
  url = "functions/localPath.php\?path=";
  if(path != ""){
    url = url + path;
  }
  console.log(url);
   ajax.open(method,url,asynchronous);
  ajax.send();
  var data = JSON.parse(ajax.responseText);
  console.log(data)
  tableData = data;
  populateTable();
  addRowHandlers();
}

function populateTable() {
  table.innerHTML = '';
  for (let data of tableData) {
    let row = table.insertRow(-1);
    let name = row.insertCell(0);
    if(data == "."){
      name.innerHTML = `<i class="fa fa-repeat"></i>`;
    }else if(data== ".."){
      name.innerHTML = `<i class="fa fa-fast-backward"></i>`;
    }else{
      name.innerHTML = data;
    }
  }
  filterTable();
}

function isVideo (str){
  if (str.endsWith(".dpx")){return true}
  if (str.endsWith(".mp4")){return true}
  if (str.endsWith(".mov")){return true}
  if (str.endsWith(".mxf")){return true}

  return false
}
function isAudio (str){
  if (str.endsWith(".wav")){return true}
  if (str.endsWith(".aac")){return true}
  if (str.endsWith(".mp3")){return true}

  return false
}




function filterTable() {
  let filter = input.value.toUpperCase();
  rows = table.getElementsByTagName("TR");
  let flag = false;
  for (let row of rows) {
    let cells = row.getElementsByTagName("TD");
    for (let cell of cells) {
      if (cell.innerHTML ==  `<i class="fa fa-repeat"></i>` || cell.innerHTML == `<i class="fa fa-fast-backward"></i>`){
        flag = true;
      }else if(cell.textContent.toUpperCase().indexOf(filter) > -1){
        flag = true;
      } else {
        cell.style.backgroundColor = '';
      }
    }

    if (flag) {
      row.style.display = "";
    } else {
      row.style.display = "none";
    }

    flag = false;
  }
}


input.addEventListener('keyup', function(event) {
  filterTable();
});



function addRowHandlers() {
  var rows = table.getElementsByTagName("tr");  
  for (i = 0; i < rows.length; i++) {
      var currentCell = table.rows[i].cells[0];
      var createClickHandler = 
          function(row) 
          {
              return function() { 
                var indexTable = this.parentElement.rowIndex;
                var id = table.rows[indexTable - 1].cells[0].innerHTML.toString();
                console.log(id)
                if((id == `<i class="fa fa-repeat"></i>`) || (id == `<i class="fa fa-fast-backward"></i>` && path == "")){
                }else if(isVideo(id) || isAudio(id)){
                  
                  var savedFile = path;
                  savedFile = path + "/" + id;
                  fileList = JSON.parse(localStorage.getItem('files'));
                  if(fileList == null){
                    fileList = [];
                    fileList.push(savedFile);
                    localStorage.setItem("files", JSON.stringify(fileList));
                    showSourceButton(fileList.length);  
                  }
                  else if (!fileList.includes(savedFile)) {
                    fileList.push(savedFile);
                    localStorage.setItem("files", JSON.stringify(fileList));
                    showSourceButton(fileList.length);   
                  }else{
                    alert("File already added!");
                  }

                }
                else if(id == `<i class="fa fa-fast-backward"></i>`){
                  path = path.split("/");
                  if(length.path != 1){
                    path.pop();
                  }
                  path = path.join('/');
                }else{
                  path = path + "/" + id;
                }
                console.log(url);
                input.value = "";
                if(path == "/Avid MediaFiles"){
                  path = "";
                }
                getDirectories(path);
                };
          };       
      currentCell.onclick = createClickHandler(currentCell);
  }
}
window.onload = addRowHandlers();

console.log(path);





function showSourceButton(quantity) {
    const element = document.getElementById("display-source");
    if(quantity == 0 && element){
        element.remove();
    }else if (quantity==0){
    }else{
        container.innerHTML = "<button class=\"display-source\" id=\"display-source\">"+ quantity +"</button>";
        var btn = document.getElementById("display-source");
        btn.onclick = function() {
            createTableFileList();
            modalSource.style.display = "block";
        }
    }
};


var modalSource = document.getElementById("modaSource");
var span = document.getElementsByClassName("close")[0];


// When the user clicks on <span> (x), close the modalSource
span.onclick = function() {
  modalSource.style.display = "none";
}

// When the user clicks anywhere outside of the modalSource, close it
window.onclick = function(event) {
  if (event.target == modalSource) {
    modalSource.style.display = "none";
  }
} 


var indexFileList, tableFileList = document.getElementById('sourceTable');

function createTableFileList() {
    fileList = JSON.parse(localStorage.getItem('files'));
    tableFileList.innerHTML = '';
    for (let data of fileList) {
      let row = tableFileList.insertRow(-1);
      let name = row.insertCell(0);
      name.innerHTML = `<i class="fa fa-trash"></i><input type="hidden" value="${data}">` + data.split("/").pop(); // to jakos fajnie byloby ogarnac. moze wgl cos lepiej jakies id dac (do usuwania) czy cos zeby to lepiej wygladalo..
      

    }
    removeRow();
    showSourceButton(fileList.length);   
}
  
function removeRow() {

    for(var i = 0; i < tableFileList.rows.length; i++)
    {
        tableFileList.rows[i].cells[0].onclick = function()
        {
            row = this.parentElement;
            var file = row.querySelector('input').value.toString();
            fileList = JSON.parse(localStorage.getItem('files'));
            fileList = fileList.filter(e => e !== file);
            localStorage.setItem("files", JSON.stringify(fileList));   
            createTableFileList();
            if(fileList==0){
              modalSource.style.display = "none";
            }
        };
    }

}
