<script setup>
import { ref } from 'vue'
const application_time = ref('');
const applications = ref([
    {
        application_id: 1,
        application_name: 'ORION',
        application_description: 'DESCK',
        user_id: 1,
        application_type: 'Descktop',
        application_budget: 120000,
        application_time: '12-02-2004',
    },
]);
const fetchData = async (applicationId) => {
    try {
        const response1 = await axios.get('http://localhost:8090/api/applications');
        console.log(response1)
        applications.value = response1.data;
        console.log('Raw response data:', cakes.value); 
        
    } catch (err){
        err.value = 'Ошибка загрузки данных: ' + err.message;
    } finally {
        loading.value = false;
    }
}  
const Confirm = async () => {
  try {
    const response = await axios.post(`http://localhost:8090/api/confirm/`, {
      application_status: "confirmed",
      application_time: application_time.value,
      application_id: applicationId
    });
  } catch (error) {
    errorMessage.value = 'Ошибка -' + error.response.data.message || error.message;
    console.error('Ошибка:', error);
  }
};
</script>
<template>
<div class="wrapper">
    <div class="projects-block">
            <div v-for="application in applications" :key="application.application_id" class="project">
                <div class="proj-field">
                    <h1 class = "proj-text-name">Название проекта</h1>
                    <h1 class = "proj-text-name">{{ application.application_name }}</h1>
                </div>
                <hr>
                <div class="proj-field">
                    <p class = "proj-text-name">Описание</p>
                    <p class="proj-text-name">{{application.application_description}}</p>
                </div>
                <hr>
                <div class="proj-field">
                    <p class = "proj-text-name">Пользователь</p>
                    <p class = "proj-text-name">{{ application.user_id }}</p>
                </div>
                <hr>
                <form @submit.prevent="Confirm(application.application_id)" class="proj-field">
                    <p class = "proj-text-name">Дедлайн пользователя</p>
                    <p class="proj-text-name">{{ application.application_time }}</p>
                    <p class="proj-text-name">{{ application.application_ai_time }}</p>
                    <input v-model ="application_time" type="date" class="proj-text-name">
                </form>
                <hr>
                <div class="proj-field">
                    <p class = "proj-text-name">Тип проекта</p>
                    <p class = "proj-text-name">{{ application.application_type }}</p>
                </div>
                <hr>
                <div class="proj-field">
                    <p class = "proj-text-name">Бюджет проекта</p>
                    <p class = "proj-text-name">{{ application.application_budget }}</p>
                </div>
                <button class = "status-btn" type="submit">Подтвердить проект</button>
            </div>
        </div>
        </div>
</template>
<style scoped>
button{
    margin-top: 10px;

    background-color:  rgb(0, 135, 219);
    color: white;
    padding: 6px;
    border-radius: 16px;
    border: none;
}
input{
    padding: 6px;
    width: 100px;
    border: 1px solid rgb(0, 135, 219);
    border-radius: 16px;
    color: rgb(43, 43, 43);
    font-family: "Noto Sans", sans-serif;
    font-size: 16px;
}
.proj-text-name{
    font-size: 18px;
    color: rgb(32, 32, 32);
    font-size: 14px;
    font-family: "Noto Sans", sans-serif;
}
select{
    padding: 4px;
    width: 100px;
    border: 1px solid rgb(0, 135, 219);
    border-radius: 16px;
    color: rgb(43, 43, 43);
    font-family: "Noto Sans", sans-serif;
    font-size: 14px;
}
button:hover{
    transition: 0.1s ease;
    color:  rgb(0, 135, 219);
    background-color: white;
    outline: 1px solid  rgb(0, 135, 219);
    cursor: pointer;
}
button:active{
    transform: scale(1.02);
}
.wrapper{
    margin-left: 2%;
    margin-right: 2%;
}
hr{
    border: 1px solid black;
    opacity: 0.1;
}
.proj-field {
    display: flex;
    justify-content: space-between;
    height: 35px;
    align-items: center;
}

.proj-label {
    font-weight: bold;
    color: #333;
}
.proj-text-progress{
    color: #199547;
    font-size: 18px;
}
.proj-text {
    color: #555555;
}
.project {
    background-color: #ffffff;
    border: 1px solid rgb(0, 135, 219);
    border-radius: 8px;
    padding: 10px;
    max-width: 800px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin-left: auto;
    margin-right: auto;
}
</style>