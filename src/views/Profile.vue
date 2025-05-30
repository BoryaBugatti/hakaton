<script setup>
import { onMounted, ref, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from 'axios';
const username = ref('Данила')
const useremail = ref('sinukovdan@mail.ru')
const orion = 'ORION';


const user = ref({});
const router = useRouter();
const route = useRoute();
const sales = ref([]);
const isOwner = ref(false);
const showed = ref(false);
const avatar_url = ref('');
function logout() {
  localStorage.removeItem('user'); 
  router.push('/login'); 
}
const uploadAvatar = () => {
  if (avatar_url.value) {
    EditImage(route.params.client_id); 
    closeModal();
  } else {
    alert("Пожалуйста, введите ссылку на аватар.");
  }
};

const closeModal = () => {
  showed.value = false;
};

const ShowStatus = () => {
  showed.value = true; 
};

const EditImage = async (clientId)=>{
  try{
    await axios.patch(`http://localhost:9090/api/profile/${clientId}`, {
      client_avatar: avatar_url.value,
    })
    alert("Success")
  }
  catch(error){
    console.error('Ошибка создания пользователя:', error);
  }
}
const fetchUserData = async (clientId) => {
  try {
    const response = await axios.get(`http://localhost:9090/api/profile/${clientId}`);
    const data = response.data;
    user.value = data.client;
    project.value = data.project;
    const storedUser = JSON.parse(localStorage.getItem('user'));
    console.log('Stored user:', storedUser);
    if (storedUser && storedUser.client_id.toString() === clientId.toString()) {
      isOwner.value = true;
    } else {
      isOwner.value = false; 
    }

    console.log('Is owner:', isOwner.value);
    console.log('User data:', user.value);
    console.log('Sales data:', sales.value);
  } catch (error) {
    console.error("Ошибка при получении данных пользователя!", error);
    router.push({ name: 'Login' });
  }
}


onMounted(() => {
  const clientId = route.params.client_id;
  if (clientId) {
    fetchUserData(clientId);
  } else {
    console.error("client_id не найден в параметрах маршрута");
  }
});
watch(() => route.params.client_id, (newClientId) => {
  if (newClientId) {
    fetchUserData(newClientId);
  }
});
</script>
<template>
<div class="profile-wrapper">
    <div class="frame-wrapper">
        <img src="../../public/logo-files/frame.png" alt="" class="frame">
    </div>
    <div class="wrapper">
        <h1>Добро пожаловать, {{ username }}!</h1>
        <div class="user-info-block">
            <div class="user-avatar">
            <img class = "avatar" src="https://rossoshanskij-r20.gosweb.gosuslugi.ru/netcat_files/9/260/2186_logo.jpg" alt="">
        </div>
            <div class="info">
                <div class="email-block">
                    <h3>Ваш E-mal:</h3>
                    <p class = "email">{{ useremail }}</p>
                </div>
                <div class="btns">
                <button type="button" @click="logout">Выйти</button>
                
                    <router-link to="/applications">Заявки</router-link>
                    <router-link to="/projects-tracking">Проекты</router-link>
                </div>
            </div>
            
        </div>
        
        <h1> Ваши проекты в разработке:</h1>
        <p class="under-txt">Ниже представлены все ваши проекты</p>
        <div class="projects-block">
            <div class="project">
                <div class="proj-field">
                    <p class = "proj-text-name">Название проекта</p>
                    <p class = "proj-text-name">Проект {{ orion }}</p>
                </div>
                <hr>
                <div class="proj-field">
                    <p class = "proj-text-title">Стек проекта</p>
                    <p class = "proj-text">Python, Vue js</p>
                </div>
                <hr>
                <div class="proj-field">
                    <p class = "proj-text-title">Дата старта проекта</p>
                    <p class = "proj-text">22.03.2004</p>
                </div>
                <hr>
                <div class="proj-field">
                    <p class = "proj-text-title">Дата окончания</p>
                    <p class = "proj-text">22.04.2005</p>
                </div>
                <hr>
                <div class="proj-field">
                    <p class = "proj-text-title">Направление</p>
                    <p class = "proj-text">Десктоп</p>
                </div>
                <hr>
                <div class="proj-field">
                    <p class = "proj-text-title">Прогресс</p>
                    <p class = "proj-text-progress">10%</p>
                </div>
                <button @click ="ShowStatus"class = "status-btn" type="button">Состояние</button>
            </div>
        </div>
    </div>
</div>
</template>
<style scoped>
.btns{
    display: flex;
    gap: 8px;
    margin-top: 10px;
    align-items: center;
}
.project-title {
    font-size: 1.5em;
}
a{
    margin-top: 11px;
  background-color: rgb(0, 135, 219);
  text-decoration: none;
  color: white;
  padding:4px;
  font-family: "Noto Sans", sans-serif;
  border-radius: 24px;
  font-size: 14px;
}
a:hover{
  outline: 1px solid rgb(0, 135, 219);
  color: rgb(0, 135, 219);
  background-color: white;
  transition: 0.1s ease;
}
.status-btn:hover{
  cursor: pointer;
  background-color: rgb(0, 135, 219);
  outline: 1px solid white;
  color: white;
  transition: 0.1s ease;
}

.status-btn:active{
  transform: scale(0.95);
}
.status-btn{
  padding: 6px;
  border-radius: 16px;
  border: none;
  background-color: #ffffff;
  color: rgb(0, 135, 219);
  font-family: "Noto Sans", sans-serif;
  font-size: 16px;
  font-weight: 500;
  margin-top: 20px;
  outline: 1px solid rgb(0, 135, 219);
  }
.proj-text-name{
    font-size: 18px;
    color: rgb(0, 135, 219);
    font-size: 20px;
}
.frame{
  position: absolute;
  top:50;
  left:0;
  transition: rotate(180deg);
  width: 98vw;
  object-fit: cover;
  height: 100vh;
  z-index: -10;
}
.under-txt{
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
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

.email-block{
    margin-top: 15px;
    display: flex;
    align-items: center;
    height: 20px;
}
button{
    margin-top: 10px;

    background-color: #ff6e6e;
    color: white;
    padding: 6px;
    border-radius: 16px;
    border: none;
}
button:hover{
    transition: 0.1s ease;
    color: #ff5a5a;
    background-color: white;
    outline: 1px solid #ff5a5a;
    cursor: pointer;
}
button:active{
    transform: scale(1.02);
}
.profile-wrapper{
    display: flex;
    flex-direction: column;
}
.avatar{
    width: 80px;
    border-radius: 50px;
    border: 2px solid rgb(0, 135, 219);
}
h1{
   max-width: 800px;
    margin-left: auto;
  font-family: "Noto Sans", sans-serif;
  font-weight: 700;
  font-size: 24px;
  margin-right: auto;
  color: rgb(43, 43, 43);
}
.email{
    margin-left: 10px;
}
.info{
    height: 30px;
}
h3{
    font-family: "Noto Sans", sans-serif;
  font-weight: 700;
  font-size: 16px;
  color: rgb(43, 43, 43);
}
p{
    font-family: "Noto Sans", sans-serif;
  font-weight: 500;
  font-size: 14px;
  color: rgb(43, 43, 43);
}
.user-info-block{
    display: flex;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
    gap: 20px;
}
.wrapper{
    margin-left: 2%;
    margin-right: 2%;
}
@media screen and (max-width: 480px){
    .frame{
        display: none;
    }
    .projects{
        display: flex;
        flex-direction: column;
        max-width: 400px;
    }
}
</style>