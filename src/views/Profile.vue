<script setup>
import {ref} from 'vue'
const username = ref('Данила')
const useremail = ref('sinukovdan@mail.ru')
</script>
<template>
<div class="profile-wrapper">
    
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
                <button type="button">Выйти</button>
            </div>
            
        </div>
        
        <h1> Ваши проекты в разработке:</h1>
        <div class="projects-block">
            <div class="project">
                <div class="proj-field">
                    <p class = "proj-text">Название проекта</p>
                    <p class = "proj-text">проект 1</p>
                </div>
                <hr>
                <div class="proj-field">
                    <p class = "proj-text">Стек проекта</p>
                    <p class = "proj-text">Python, Vue js</p>
                </div>
                <hr>
                <div class="proj-field">
                    <p class = "proj-text">Дата старта проекта</p>
                    <p class = "proj-text">22.03.2004</p>
                </div>
                <hr>
                <div class="proj-field">
                    <p class = "proj-text">Дата окончания</p>
                    <p class = "proj-text">22.04.2005</p>
                </div>
                <hr>
                <div class="proj-field">
                    <p class = "proj-text">Направление</p>
                    <p class = "proj-text">Десктоп</p>
                </div>
                <hr>
                <div class="proj-field">
                    <p class = "proj-text">Прогресс</p>
                    <p class = "proj-text-progress">10%</p>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<style scoped>
.proj-text-progress{
    max-width: 150px;
    margin-right: 20px;
    margin-left: 20px;
    height: 20px;
    color: rgb(37, 158, 37);
}
.proj-field{
    display: flex;
    flex-direction: column;
    align-items: center;
}
.proj-text{
    max-width: 150px;
    margin-right: 20px;
    margin-left: 20px;
    height: 20px;
}
.project{
    display: flex;
    width: 90%;
    border: 1px solid black;
    padding: 2px;
    border-radius: 32px;
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
    border: 2px solid rgb(0, 135, 219);;
}
h1{
  font-family: "Noto Sans", sans-serif;
  font-weight: 700;
  font-size: 24px;
  margin-left: auto;
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
    margin-left: auto;
    margin-right: auto;
    gap: 20px;
}
.wrapper{
    margin-left: 5%;
}
</style>