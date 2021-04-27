
function ready() {
    fetch('back.php?mode=masters_id')
                .then((response) => {
                    return response.text();
                })
                .then((data) => {
                    //console.log(data);
                    showStartData(data);
                });

  }

document.addEventListener("DOMContentLoaded", ready);




function getByMasterId(){

    let id = document.getElementById('master').value;
    //console.log(id);

    fetch('back.php?mode=getByMastersId', {
                        method: 'POST',
                        body: id,
                })
                .then((response) => {
                    return response.text();
                })
                .then((data) => {
                    console.log(data);
                    showTableById(data);
                });

}


function showStartData(data){
    let dataBlocks = data.split('----');
    let masters = dataBlocks[0].split('\n');
    let dataByTable = dataBlocks[1].split('\n');

    let selectBlock = `<option value="all"> Choise your pokemon </option>`;

    for (i = 0; i < masters.length-1; i++){
        masters[i] = masters[i].split("|");
        selectBlock += `<option value="${masters[i][0]}"> ${ masters[i][1]} </option>`
    }
    //console.log( masters);
    console.log(dataByTable);

    document.getElementById('master').innerHTML = selectBlock;



    ShowTable(dataByTable);

}


function showTableById(data){
    if (data == 'NotFound'){
        document.getElementById('content').innerHTML = '';
        alert("Информации об оказанных услугах этого мастера у нас нет");
    } else{
        let dataByTable = data.split('\n');
        ShowTable(dataByTable);
    }
}

function ShowTable(dataByTable){

    let tableBlock = `<table class='input-table'>`
    
    for (i = 0; i < dataByTable.length-1; i++){
        dataByTable[i] = dataByTable[i].split("|");
        let row = `<td>${dataByTable[i][1]}</td>\n`;

        for (j = 2; j < dataByTable[i].length; j++){
            row += `<td id='x'> <label>${dataByTable[i][j]}</label></td>\n`;
        }
        tableBlock += `<tr scope="row"> ${row} </tr>`;
    }

    tableBlock += `</table>`;
    document.getElementById('content').innerHTML = tableBlock;
}