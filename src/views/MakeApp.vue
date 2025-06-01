<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
const isRegistered = ref(false);
const projectName = ref('');
const projectDescription = ref('');
const projectBudget = ref('');
const projectType = ref('');
const projectDeadline = ref('');
const aiNeeded = ref(false);
const storedUser = JSON.parse(localStorage.getItem('user'));
const submitForm = async () => {
    if (!storedUser) {
        alert('Пожалуйста, зарегистрируйтесь, чтобы сделать заказ.');
        return;
    }
    try {
            if(aiNeeded.value == True){
                const response = await axios.post('http://localhost:8090/api/make-app/aiaiai', {
                            project_name: projectName.value,
                            project_description: projectDescription.value,
                            project_budget: projectBudget.value,
                            project_type: projectType.value,
                            project_deadline: projectDeadline.value,
                    });
                    if (!response.ok) {
                        throw new Error('Ошибка при отправке формы');
                    }
                    alert('Форма успешно отправлена!');
                    resetForm();
            }
            else{
                const response = await axios.post(`http://localhost:8090/api/make-app/manager`, {
                            project_name: projectName.value,
                            project_description: projectDescription.value,
                            project_budget: projectBudget.value,
                            project_type: projectType.value,
                            project_deadline: projectDeadline.value,
                    });
                    if (!response.ok) {
                        throw new Error('Ошибка при отправке формы');
                    }
                    alert('Форма успешно отправлена!');
                    resetForm();
                
            }
        }catch (error) 
        {
            alert(error.message);
        }
}
function resetForm(){
    projectName.value = '';
    projectDescription.value = '';
    projectBudget.value = '';
    projectType.value = '';
    projectDeadline.value = '';
    supportNeeded.value = false;
};
</script>
<template>
    <div class="wrapper">
    <div class="application-form-wrapper">
        
        <form action="">
            <h1>Давайте отправим вашу заявку:</h1>
            <p>Введите название вашего проекта:</p>
            <input v-model="projectName" type="text" placeholder="Название вашего проекта">
            <p>Введите описание проекта:</p>
            <input v-model="projectDescription" type="text" placeholder="Описание проекта">
            <p>Введите бюджет проекта:</p>
            <input v-model="projectBudget" type="text" placeholder="Бюджет проекта">
            <p>Выберете тип проекта:</p>
            <select v-model="projectType" name="" id="">
                <option value="Desctop">Десктоп</option>
                <option value="Web">Веб-приложение</option>
                <option value="Mobile">Мобильное приложение</option>
                <option value="Integration">Интеграция систем</option>
            </select>
            <div class="date-text">
                <p>Введите желательный срок исполнения:</p>
                <p class = "gray-txt">Мы не можем гарантировать, что в выбранную вами дату проект закончится, потому что основная дата конца проекта определяется по вашему описанию.</p>
                <p class = "gray-txt">Наши менеджеры подберут команду специально для вас!</p>
            </div>
            <input v-model="aiNeeded" type="date" class = "date-input">
            <div class="checkbox-block">
                <p>Хотите чтобы рассчет времени провел Менеджер?</p>
                <input type="checkbox" class = "checkbox">
            </div>
            <button type="submit">Отправить</button>
        </form>
        <div class="img-block">
            <img class = "main-page-img"src="../../public/logo-files/programming2.png" alt="">
        </div>
        </div>
    </div>
</template>
<style scoped>
.checkbox-block{
    display: flex;
    align-items: center;
    gap: 0px;
}
.img-block{
    display: flex;
    flex-direction: column;
}
.main-page-img{
    width: 500px;
    height: auto;
}
.checkbox {
    appearance: none;
    width: 1rem;
    height: 1rem;
    border: 1px solid #0d6efd;
    border-radius: 0.25rem;
    background-color: #fff;
    cursor: pointer;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    margin: 0;
    margin-left: 10px;
}
button{
    max-width: 400px;
    background-color: #0d6efd;
    color: white;
    padding: 6px;
    border-radius: 16px;
    border: none;
}
button:hover{
    transition: 0.1s ease;
    color: #0d6efd;
    background-color: white;
    outline: 1px solid #0d6efd;
    cursor: pointer;
}
button:active{
    transform: scale(1.02);
}
h1{
    font-family: "Noto Sans", sans-serif;
  font-weight: 800;
  font-size: 26px;
  color: rgb(43, 43, 43);
}
select{
    padding: 6px;
    width: 410px;
    border: 1px solid rgb(0, 135, 219);
    border-radius: 16px;
    color: rgb(43, 43, 43);
    font-family: "Noto Sans", sans-serif;
    font-size: 16px;
}
.checkbox:checked{
    background-color: #0d6efd;
    border-color: #0d6efd;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='m6 10 3 3 6-6'/%3e%3c/svg%3e");
}

.date-input{
    margin-top:80px;
}
.wrapper{
    display: flex;
}
.application-form-wrapper{
    display: grid;
    grid-template-columns: auto auto;
    align-items: center;
    margin-bottom: 20px;
    margin-left: auto;
    margin-right: auto;
}
.date-text{
    height: 40px;
}
form{
    display: flex;
    flex-direction: column;
    margin-left: auto;
    margin-right: auto;
    margin-left: 10px;
}
input{
    padding: 6px;
    width: 400px;
    border: 1px solid rgb(0, 135, 219);
    border-radius: 16px;
    color: rgb(43, 43, 43);
    font-family: "Noto Sans", sans-serif;
    font-size: 16px;
}
p{
  color: rgb(43, 43, 43);
  font-family: "Noto Sans", sans-serif;
  font-size: 16px;
}
.gray-txt{
  color: rgb(166, 166, 166);
  font-family: "Noto Sans", sans-serif;
  font-size: 12px;
  max-width: 600px;
}
@media screen and (max-width: 480px){
    .gray-txt{
        max-width: 300px;
    }
    .application-form-wrapper{
        display: flex;
    }
    button{
        max-width: 300px;
    }
    .main-page-img{
    display: none;
}
    h1{
        font-size: 20px;
    }
    input{
        width: 300px;
    }
    select{
        width: 300px;
    }
    form{
        margin-left: 20px;
    }
    .date-input{
        margin-top: 140px;
    }
}
@media screen and (max-width: 1023px){
    .application-form-wrapper{
        display: flex;
    }
     .main-page-img{
    display: none;
}
    
}
</style>