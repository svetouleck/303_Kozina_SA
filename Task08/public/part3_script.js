
function evaluateLists(){
    fetch('part3_back.php?mode=masters')
                .then((response) => {
                    return response.text();
                })
                .then((data) => {
                    console.log(data);
                    let new_data = parseData(data);
                    evaluateMasters(new_data);
                });

    /*let now = new Date();
    let str_date = `${now.getFullYear()}-${now.getMonth()}-${now.getDate()}`
    document.getElementById('date').min = str_date;*/
    
}

document.addEventListener("DOMContentLoaded", evaluateLists);

function parseData(data){
    let dataByTable = data.split('\n');
    for (i = 0; i < dataByTable.length-1; i++){
        dataByTable[i] = dataByTable[i].split("|");
        //services.push(dataByTable[i]);
    }
    return(dataByTable);
}

function evaluateService(data){

    let selectBlock = `<option value='all'>Provided service*</option>`;

    for (i = 0; i < data.length-1; i++){
        selectBlock += `<option value="${data[i][0]}"> ${data[i][1]} </option>`
    }

    document.getElementById("service").innerHTML = selectBlock;
    //document.getElementById('specialization').innerHTML = selectBlock;
}

function evaluateMasters(data){

    let selectBlock = `<option value='all'>Master*</option>`;

    for (i = 0; i < data.length-1; i++){
        selectBlock += `<option value="${data[i][0]}"> ${data[i][1]} </option>`
    }

    document.getElementById("master").innerHTML = selectBlock;
    //document.getElementById('specialization').innerHTML = selectBlock;
}

function getServices(){
    let master_id = document.getElementById('master').value; 

    fetch('part3_back.php?mode=service', {
        method: "POST",
        body: JSON.stringify({'id' : master_id})
      })
        .then((response) => {
            return response.text();
        })
        .then((data) => {
            console.log(data);
            let new_data = parseData(data);
            evaluateService(new_data);
        });
}

function getTimes(){
    let master_id = document.getElementById('master').value; 
    let service_id = document.getElementById('service').value; 
    let date = document.getElementById('date').value; 

    console.log(date);
}

function submitData(){
    let master_id = document.getElementById('master').value; 
    let service_id = document.getElementById('service').value; 
    let date = document.getElementById('date').value; 
    console.log(date);

}

