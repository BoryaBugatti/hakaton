<script setup>
import Header from './components/Header.vue'
</script>

<template>
<div class="app">
  <div class="app-wrapper">
    <Header/>
    <router-view>

    </router-view>
    <div class="footer">
      <div class="info">
          <h3>© 2025 Менеджинг проектов. Все права защищены.</h3>
      </div>
      <div class="btns-foot">
        
        <a href="#">ВКонтакте</a>
        <a href="#">Телеграмм</a>
        <a href="#">Контакты</a>
        
      </div>
    </div>
  </div>
</div>
</template>

<style scoped>

@import url('https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap');
.app{
  position: absolute;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  overflow-x: hidden;
}
a{
  display: flex;
  align-items: center;
  font-family: "Noto Sans", sans-serif;
  font-weight: 500;
  font-size: 14px;
  text-decoration: none;
  color: rgb(255, 255, 255);
  background-color: rgb(0, 135, 219);
  padding: 6px;
  border-radius: 16px;
}
a:hover{
  color: rgb(0, 135, 219);
  outline: 1px solid rgb(0, 135, 219);
  background-color: white;
  transition: 0.1s ease;
}
h3{
font-family: "Noto Sans", sans-serif;
  font-weight: 500;
  font-size: 16px;
  color: rgb(43, 43, 43);
}
.footer{
  margin-bottom: 10px;
  position: relative;
  width: auto;
  display: flex;
  flex-direction: column;
  align-items: center;
}
.info{
  display: flex;
  margin-left: auto;
  margin-right: auto;
}
.btns-foot{
  display: flex;
  gap: 10px;
  margin-left: auto;
  margin-right: auto;
}
</style>