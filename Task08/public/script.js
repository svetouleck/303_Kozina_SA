
function masterForm(){
    let services = [];
    let specializations = [];
    let selectsCount = 0;
    let selectsId = [];

    let week = ['Monday :', 'Tuesday :', 'Wednesday :', 'Thursday :', 
                'Friday :', 'Saturday :', 'Sunday :']
    let weekBlock = ``

    for (i = 0; i<week.length; i++){
        weekBlock += `
        <div id="${week[i].slice(0, -2)}">
            <label id='mon-label'> ${week[i].slice()}</label>
            <input type="time" id='${week[i].slice(0, 3).toLowerCase()}-start'> -
            <input type="time" id='${week[i].slice(0, 3).toLowerCase()}-end' >
        </div>
        `
    }
    let block = `
        <div class='person-info'>
                <label class='rl'> 1. Personal Information </label><hr><br>
                <input type='text' id='name' placeholder='Name*'><br>
                <input type='text' id='last-name' placeholder='Last name*'><br> 
                <input type='text' id='patronymic' placeholder='Patronymic'><br>
                <div class="gender">
                    <p><input type="radio" name='gender' id="male" checked>Male</p>
                    <p><input type="radio" name='gender' id="female">Female</p>
                </div>
                <select id = "specialization">
                    <option value='all'>Specialization*</option>
                </select><br>
                <input type='number' id='percent' placeholder='percent of profit*'><br>
            </div><br>
            <div class="shedule">
                <label class='rl'>2. Schedule information</label><hr><br>
                <div id="week">
                    ${weekBlock}
                </div>
            </div><br>
            <div class="work-info">
                <label class='rl'> 3. Work Skills </label><hr><br>
                <div id="lists">
                    
                </div><br>
                <label id='add'>add item</label>
            </div><br>
            <input type='button' id='btn' value='register'">
    `
    document.getElementById("content").innerHTML = block;
    document.addEventListener("DOMContentLoaded", evaluateLists);
    document.querySelector(`#btn`).addEventListener('click', submitData);
    document.querySelector(`#add`).addEventListener('click', addServiceChoice);

    function evaluateLists(){
        fetch('back.php?mode=services')
                    .then((response) => {
                        return response.json();
                    })
                    .then((data) => {
                        //console.log(data);
                        //console.log(data['services']);
                        services = data['services'];
                        //evaluateService(data['services'])
                    });
    
        fetch('back.php?mode=specialization')
                    .then((response) => {
                        return response.json();
                    })
                    .then((data) => {
                        //console.log(data);
                        //parseSpecialization(data);
                        evaluateSpecialization(data['specializations']);
                    });
    }

    function evaluateSpecialization(data){
        let selectBlock = `<option value='all'>Specialization*</option>`;
    
        for (item in data){
            selectBlock += `<option value="${item}"> ${data[item]} </option>`
        }
        document.getElementById('specialization').innerHTML = selectBlock;
    }

    function evaluateService(data){
        let selectBlock = `<option value='all'>Provided service*</option>`;
    
        for (item in data){
            selectBlock += `<option value="${item}"> ${data[item]} </option>
            `
        }
    
        return(selectBlock);
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
        
        document.querySelector(`#img-${selectsCount}`).addEventListener('click', function(e){
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

        if (spec == 'all') return;

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

        let shedule = {};

        for (i=0; i<7; i++){
            let id_prefix = week[i].slice(0, 3).toLowerCase();
            
            let start = document.getElementById(`${id_prefix}-start`).value;
            let end = document.getElementById(`${id_prefix}-end`).value;
            if (start==='' && end!=='' || start!=='' && end===''){
                alert(`Необходимо заполнить либо начало и конец рабочего дня одновременно, либо не заполнять ничего`);
                return
            }
            if (start==='' && end ===''){
                start = 'NULL';
                end = 'NULL';
            }

            shedule[id_prefix] = [start, end];
        }

        let master = {
            'name' : name,
            'lastName' : lastName,
            'patronymic' : patronymic,
            'percent' : percent,
            'spec' : spec,
            'gender' : gender,
            'services' : userServiceId,
            'shedule' : shedule
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
                        alert("Информация успешно внесена в БД!")
            
                    });


    }

    evaluateLists();
}






function preEntryForm() {
    let block = `
    <div class="according">
        <label > Sign up for a service </label><hr><br>
        <select id = "master">
            <option value='all'>Master*</option>
        </select><br>
        <select id = "service">
            <option value='all'>Service*</option>
        </select><br>
        <select id = "period">
        <option value='all'>Recording time*</option>
        </select><br>
    </div>
    <input id='submit' type='button' value='register'>
    `
    document.getElementById("content").innerHTML = block;

    function evaluateLists(){
        fetch('back.php?mode=masters')
                    .then((response) => {
                        return response.json();
                    })
                    .then((data) => {
                        //console.log(data);
                        evaluateMasters(data['masters']);
                    });
    }

    function evaluateMasters(data){
        let selectBlock = `<option value='all'>Master*</option>`;
    
        for (item in data){
            //console.log(item, data[item])
            let FIO_parse = data[item].split(" ");
            if (FIO_parse[2] != 'NULL')
                selectBlock += `<option value="${item}"> ${data[item]} </option>`
            else
                selectBlock += `<option value="${item}"> ${FIO_parse[0]}  ${FIO_parse[1]}</option>`
            
            }
    
        document.getElementById("master").innerHTML = selectBlock;
        //document.getElementById('specialization').innerHTML = selectBlock;
    }

    function getServices(){
        let master_id = document.getElementById('master').value; 
        if (master_id == 'all') return;
    
        fetch('back.php?mode=service-for-master', {
            method: "POST",
            body: JSON.stringify({'id' : master_id})
          })
            .then((response) => {
                return response.json();
            })
            .then((data) => {
                //console.log(data);
                //let new_data = parseData(data);
                evaluateService(data['services']);
            });
    }

    function evaluateService(data){
        let selectBlock = `<option value='all'>Provided service*</option>`;
    
        for (item in data){
            //let is_selected = '';
            selectBlock += `<option value="${item}"> ${data[item]} </option>`
        }
        document.getElementById("service").innerHTML = selectBlock;
    }

    function getTimes(){
        let master_id = document.getElementById('master').value; 
        let service_id = document.getElementById('service').value;
        //if (master_id == 'all') return;
        if (service_id == 'all') return;
    
        let now = new Date();
        let str_date = `${now.getFullYear()}-${("0" + (now.getMonth() + 1)).slice(-2)}-${("0" + now.getDate()).slice(-2)}`;


        fetch('back.php?mode=get_times', {
            method: "POST",
            body: JSON.stringify({
                'master_id' : master_id,
                'service_id' : service_id,
                'date' : str_date,
                'week_day' : now.getDay()
            })
          })
            .then((response) => {
                return response.json();
            })
            .then((data) => {
                //console.log(data);
                if (!!data['error']){
                    alert(data['error']);
                    return;
                }
               else
                evaluatePeriods(data['periods']);
                
            });
    }

    function evaluatePeriods(data){
        let selectBlock = `<option value='all'>Recording time*</option>`;
    
        for (item in data){
            selectBlock += `<option value="${data[item]['start']}"> ${data[item]['start']} - ${data[item]['end']} </option>`
        }
        document.getElementById("period").innerHTML = selectBlock;
    }
    

    document.querySelector(`#master`).addEventListener('change', getServices);
    document.querySelector(`#service`).addEventListener('change', getTimes);
    document.querySelector(`#submit`).addEventListener('click', submitData);
    evaluateLists();

    function submitData(){
        let master_id = document.getElementById('master').value;
        let service_id = document.getElementById('service').value;
        let now = new Date();
        let str_date = `${now.getFullYear()}-${("0" + (now.getMonth() + 1)).slice(-2)}-${("0" + now.getDate()).slice(-2)}`;
        let period_start = document.getElementById('period').value;
        if (period_start == 'all') return;
        //console.log(period_start);

        let accord = {
            'master_id' : master_id,
            'service_id' : service_id,
            'time' : period_start, 
            'date' : str_date
        }

        console.log(accord);
        fetch('back.php?mode=submit_work', {
            method: "POST",
            body: JSON.stringify(accord)
          })
            .then((response) => {
                return response.text();
            })
            .then((data) => {
                alert("Информация успешно внесена в БД!")
            });
    }



}

function menuON() {
    let block = `
    <div class='menu-container'>
        <label id=menu-0>Menu</label><br>
        <label id=menu-1>1. Master’s form</label><br>
        <label id=menu-2>2. Pre-entry</label><br>
    </div>
    <img id='menu-off' src="list-img.png">
    `
    document.getElementById("menu").innerHTML = block;
    document.querySelector(`#menu-off`).addEventListener('click', menuOFF);
    document.querySelector(`#menu-1`).addEventListener('click', masterForm);
    document.querySelector(`#menu-2`).addEventListener('click', preEntryForm);
}

function menuOFF() {
    let block = `
    <img id='menu-on' src="list-img.png"> 
    `
    document.getElementById("menu").innerHTML = block;
    document.querySelector(`#menu-on`).addEventListener('click', menuON);
}

document.addEventListener("DOMContentLoaded", masterForm);
document.querySelector(`#menu-on`).addEventListener('click', menuON);

