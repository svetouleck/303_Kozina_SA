let services = [];
let specializations = [];
let selectsCount = 0;
let selectsId = [];


function evaluateLists(){
    fetch('back.php?mode=services')
                .then((response) => {
                    return response.text();
                })
                .then((data) => {
                    console.log(data);
                    parseService(data);
                });

    fetch('back.php?mode=specialization')
                .then((response) => {
                    return response.text();
                })
                .then((data) => {
                    console.log(data);
                    parseSpecialization(data);
                    evaluateSpecialization();
                });
}

document.addEventListener("DOMContentLoaded", evaluateLists);

function parseService(data){
    let dataByTable = data.split('\n');
    for (i = 0; i < dataByTable.length-1; i++){
        dataByTable[i] = dataByTable[i].split("|");
        services.push(dataByTable[i]);
        }
    //console.log(dataByTable);
}

function parseSpecialization(data){
    let dataByTable = data.split('\n');
    for (i = 0; i < dataByTable.length-1; i++){
        dataByTable[i] = dataByTable[i].split("|");
        specializations.push(dataByTable[i]);
        }
    console.log(dataByTable);
}

function evaluateSpecialization(){

    let selectBlock = `<option value='all'>Specialization*</option>`;

    for (i = 0; i < specializations.length; i++){
        selectBlock += `<option value="${specializations[i][0]}"> ${specializations[i][1]} </option>`
    }
    document.getElementById('specialization').innerHTML = selectBlock;
}

function evaluateService(data){

    let selectBlock = `<option value='all'>Provided service*</option>`;

    for (i = 0; i < data.length; i++){
        selectBlock += `<option value="${data[i][0]}"> ${data[i][1]} </option>`
    }

    return(selectBlock);
    //document.getElementById('specialization').innerHTML = selectBlock;
}

function addServiceChoice(){
    selectsCount++;
    let div_id = `select-${selectsCount}`;
    let innerBlock = evaluateService(services);


    block = `<div id='div-${selectsCount}'>
                <select id=${div_id}>
                    ${innerBlock}
                <select>
                <img id='img-${selectsCount}' src='del.png' > <br>
            </div>
            `;
    selectsId.push(div_id);
    document.getElementById('lists').insertAdjacentHTML('beforeend', block);
    
    document.querySelector(`#img-${selectsCount}`).addEventListener('click', function(e){ // Вешаем обработчик клика на UL, не LI
        let parent_id = e.target.parentElement.id; 
        console.log(parent_id);
        let to_delete = e.target.parentElement.childNodes[1].id;

        let index = selectsId.indexOf(to_delete);
        if (index > -1) {
            selectsId.splice(index, 1);
        }

        document.getElementById(parent_id).remove();
    });
}

function submitData(){

    let name = document.getElementById('name').value;
    let lastName = document.getElementById('last-name').value;
    let patronymic = document.getElementById('patronymic').value;
    let spec = document.getElementById('specialization').value;
    let percent = document.getElementById('percent').value;
    let gender = '';

    let genderDict = {
        'male': 'М',
        'female': 'Ж',
    }

    let rad=document.getElementsByName('gender');
    for (var i=0;i<rad.length; i++) {
        if (rad[i].checked) {
            gender = genderDict[rad[i].id];
            //console.log(rad[i].id, gender);
        }
    }

    if (name === '' || lastName === '' || 
         spec === '' || percent === ''){
             alert("Заполните все обязательные поля!");
             return;
    };

    let userServiceId = [];
    console.log(selectsId);
    for (i=0; i<selectsId.length; i++){
        let selectId = document.getElementById(selectsId[i]).value;
        if (selectId === 'all'){
            alert("Заполните все обязательные поля!");
            return;
        }
        userServiceId.push(Number(selectId));
        //console.log(selectId)
    }

    userServiceId = Array.from(new Set(userServiceId));
    console.log(userServiceId);

    if (patronymic === ''){
        patronymic = 'NULL';
    }

    let master = {
        'name' : name,
        'lastName' : lastName,
        'patronymic' : patronymic,
        'percent' : percent,
        'spec' : spec,
        'gender' : gender,
        'services' : userServiceId
    }

    console.log(master);

    fetch('back.php?mode=submit', {
        method: "POST",
        body: JSON.stringify(master)
      })
                .then((response) => {
                    return response.text();
                })
                .then((data) => {
                    console.log(data);
                    //parseService(data);
                });


}