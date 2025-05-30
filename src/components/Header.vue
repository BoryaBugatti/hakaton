<script setup>
</script>
<template>
<div class="header">
        <div class="logo-block">
            <img src="../../public/logo-files/logo-blue.png" alt="logo">
            <h1>ProMan</h1>
        </div>
        <ul>
            <li>
            <router-link to="/" href="#">
              <div class="btn">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                          </svg>
              <p class = "btn-txt">Главная</p>
              </div></router-link>
          </li><li>
            <router-link to="/make-app">
              <div class="btn">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
              </svg>
              <p class = "btn-txt">Создать заявку</p>
              </div></router-link>
          </li><li>
            <router-link to="/profile">
              <div class="btn">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
              <p class = "btn-txt">Профиль</p>
              </div></router-link>
          </li>
        </ul>
    </div>
    <hr>
</template>
<style scoped>
.btn{
  display: flex;
  align-items: center;
  height: 20px;
}
svg{
  margin-right: 4px;
}
hr{
  border: 1px solid black;
  opacity: 0.1;
  width: 90vw;
  margin-left: auto;
  margin-right: auto;
}
a:hover{
  color: rgb(0, 135, 219);
  outline: 1px solid rgb(0, 135, 219);
  transition: 0.1s ease;
}
a{
  display: flex;
  align-items: center;
  font-family: "Noto Sans", sans-serif;
  font-weight: 500;
  font-size: 14px;
  text-decoration: none;
  color: rgb(43, 43, 43);
  padding: 6px;
  border-radius: 16px;
}
img{
  width: 40px;
}
h3{
  font-family: "Noto Sans", sans-serif;
  font-weight: 500;
  font-size: 12px;
  color: rgb(43, 43, 43);
}
h1{
  font-family: "Noto Sans", sans-serif;
  font-weight: 800;
  color: rgb(0, 135, 219);
}

.logo-block{
  display: flex;
  align-items: center;
}
.header{
  margin-top: 10px;
  height: 50px;
  width: 95vw;
  margin-left: auto;
  margin-right: auto;
  align-items: center;
  display: flex;
  justify-content: space-between;
  z-index: 10;
}
ul{
  align-items: center;
  list-style: none;
  display: flex;
  gap: 8px;
}
@media screen and (max-width: 480px){
	.btn-txt{
    display: none;
  }
  svg{
    height: 25px;
    width: 25px;
    margin: 0;
  }
  ul{
    align-items: center;
    list-style: none;
    display: flex;
    gap: 20px;
  }
}
</style>