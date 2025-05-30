<script setup>
import TheWelcome from '../components/TheWelcome.vue'



</script>

<template>
  <div class="wrapper">
  <div class="frame-wrapper">
    <img src="../../public/logo-files/frame.png" alt="" class="frame">
  </div>
  <main>
    <div class="left-info-side">
      <h1 class = "main-title">
        Управляйте Вашими 
        <span>IT проектами</span>
         Эффективно и Удобно!
      </h1>
      <p class = "main-page-info">Добро пожаловать в нашу платформу по менеджингу IT проектов! Мы предлагаем мощные инструменты и интуитивно понятный интерфейс для планирования, отслеживания и управления вашими проектами.</p>
      <p class = "gray-txt">В нашей системе вы найдете все необходимые инструменты для успешного управления IT проектами.</p>
      
      <div class="btns">
        <router-link to="/make-app">Создайте заявку!</router-link>
      </div>
    </div>
    <div class="right-info-side">
      <img class = "main-page-img"src="../../public/logo-files/programming.png" alt="">
    </div>
  </main>
  </div>
</template>
<style scoped>
.info-block-txt{
  max-width: 600px;
}
.info-block-sec{
  display: grid;
  grid-template-columns: auto auto;
}
.sec-img{
  margin-left: 100px;
  width: 400px;
  height: auto;
}

.frame{
  transition: rotate(180deg);
  width: 98vw;
  object-fit: cover;
  height: 100vh;
}
.gray-txt{
  text-decoration: none;
  color: rgb(166, 166, 166);
  padding:6px;
  font-family: "Noto Sans", sans-serif;
  border-radius: 24px;
  font-size: 12px;
}
hr{
  border: 1px solid black;
  opacity: 0.1;
}
.frame{
  position: absolute;
  top: 0;
  left: 0;
  z-index: -10;
}
.btns{
  margin-top: 15px;
}
.main-page-info{
  font-family: "Noto Sans", sans-serif;
  font-weight: 500;
  color: rgb(43, 43, 43);
}
.main-page-img{
  height: auto;
  max-width: 100%;
}
a{
  background-color: rgb(0, 135, 219);
  text-decoration: none;
  color: white;
  padding:6px;
  font-family: "Noto Sans", sans-serif;
  border-radius: 24px;
}
a:hover{
  outline: 1px solid rgb(0, 135, 219);
  color: rgb(0, 135, 219);
  background-color: white;
  transition: 0.1s ease;
}
span{
  background-color: rgb(111, 200, 255);
  color: white;
  padding: 4px;
  border-radius: 16px;
}
.left-info-side{
   height: auto;
  max-width: 100%;
}
.right-info-side{
  display: flex;
  align-items: flex-start;
}
.main-title{
  
  font-family: "Noto Sans", sans-serif;
  font-weight: 800;
  color: rgb(43, 43, 43);
}
main{
  margin-left: auto;
  margin-right: auto;
  width: 90vw;
  display: grid;
  grid-template-columns: auto auto;
}
@media screen and (max-width: 480px){
  .main-title{
    font-family: "Noto Sans", sans-serif;
    font-weight: 800;
    color: rgb(43, 43, 43);
    font-size: 20px;
  }
  main{
    display: flex;
    flex-direction: column;
    margin-left: auto;
    margin-right: auto;
    width: 95vw;
  }
  span{
    background-color: white;
    color: rgb(43, 43, 43)
  }
}
</style>
