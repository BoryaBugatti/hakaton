<script setup>
import { ref } from 'vue';
import axios from 'axios';
import router from '@/router';

const useremail = ref('');
const password = ref('');
const errorMessage = ref('');

const login = async () => {
  try {
    const response = await axios.post('http://localhost:8090/api/athorization', {
      user_email: useremail.value,
      user_password: password.value,
    });
    const userdata = response.data;
    const client_id = userdata.client_id;

    if (response.data.status === 'OK') {
      const userResponse = await axios.get(`http://localhost:8090/api/user/${client_id}`);
      const userData = userResponse.data.client;
      
      if (userData) {
        localStorage.setItem('user', JSON.stringify(userData));
        router.push({ path: `/profile/${userData.client_id}` });
      } else {
        errorMessage.value = 'Ошибка получения данных пользователя';
      }
    } else {
      errorMessage.value = response.data.status;
    }
  } catch (error) {
    errorMessage.value = error.response?.data?.status || 'Ошибка соединения с сервером';
    console.error('Ошибка входа:', error);
  }
};
</script>

<template>
  <div class="wrapper">
    <div class="login-wrapper">
      <div class="login-form">
        <form @submit.prevent="login" class="form-login">
          <h2 class="title">Авторизация</h2>
          <p class="login-title">Ваш E-mail:</p>
          <input v-model="useremail" type="email" placeholder="E-mail" class="login-input" required />
          
          <div class="pass-titles">
            <p class="pass-title">Ваш пароль:</p>
          </div>
          <input type="password" v-model="password" placeholder="Пароль" required class="password-input" />
          
          <button class="sbmt-btn" type="submit">Войти</button>
          
          <div class="reg-block">
            <p class="reg-txt">У вас нет аккаунта?</p> 
            <a class="reg-btn" href="/register">Зарегистрируйтесь!</a>
          </div>
          
          <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
        </form>
      </div>
      <hr class="line">
    </div>
  </div>
</template>

<style scoped>
.error-message {
  color: #ff6b6b;
  margin-top: 10px;
  font-family: "Noto Sans", sans-serif;
  text-align: center;
}

.reg-btn:active {
  transform: scale(0.9);
}

.reg-btn:hover {
  transition: 0.2s ease;
  cursor: pointer;
  background-color: rgb(255, 255, 255);
  color: rgb(0, 135, 219);
  outline: 1px solid white;
}

.reg-btn {
  padding: 6px;
  border-radius: 16px;
  background-color: rgb(0, 135, 219);
  color: rgb(255, 255, 255);
  border: none;
  outline: 1px solid white;
  text-decoration: none;
  font-family: "Noto Sans", sans-serif;
  margin-left: 8px;
}

.reg-block {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 10px;
}

.reg-txt {
  color: white;
  font-family: "Noto Sans", sans-serif;
  font-size: 12px;
}

.line-up {
  margin-bottom: 20px;
  opacity: 0.2;
}

.line {
  margin-top: 30px;
  opacity: 0.2;
}

.login-title {
  color: white;
  font-family: "Noto Sans", sans-serif;
}

.pass-titles {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.pass-title {
  color: white;
  font-family: "Noto Sans", sans-serif;
}

.login-form {
  display: flex;
  flex-direction: column;
  margin-left: auto;
  margin-right: auto;
}

.title {
  color: white;
  font-family: "Noto Sans", sans-serif;
  margin-bottom: 20px;
}

.sbmt-btn:hover {
  cursor: pointer;
  background-color: rgb(0, 135, 219);
  outline: 1px solid white;
  color: white;
  transition: 0.1s ease;
}

.sbmt-btn:active {
  transform: scale(0.95);
}

.sbmt-btn {
  padding: 10px 20px;
  border-radius: 16px;
  border: none;
  background-color: #ffffff;
  color: rgb(0, 135, 219);
  font-family: "Noto Sans", sans-serif;
  font-size: 16px;
  font-weight: 500;
  margin-top: 20px;
  width: 100%;
}

.password-input:focus,
.login-input:focus {
  outline: 2px solid rgb(0, 135, 219);
}

.password-input,
.login-input {
  background-color: #ffffff;
  width: 100%;
  color: rgb(48, 48, 48);
  padding: 10px 15px;
  border: none;
  border-radius: 16px;
  outline: 1px solid rgb(0, 135, 219);
  margin-top: 5px;
  margin-bottom: 15px;
  box-sizing: border-box;
}

.form-login {
  min-height: 200px;
  width: 350px;
  padding: 30px;
  background-color: rgb(0, 135, 219);
  border-radius: 24px;
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-left: auto;
  margin-right: auto;
}

.wrapper {
  width: 100vw;
  background-color: white;
  display: flex;
  min-height: 100vh;
  align-items: center;
}

.login-wrapper {
  margin-left: auto;
  margin-right: auto;
  width: 80vw;
  max-width: 1200px;
}

@media screen and (max-width: 480px) {
  .form-login {
    width: 90%;
    padding: 20px;
    margin-left: -8%;
    margin-top: -60%;
  }
  
  .wrapper {
    padding: 20px;
  }
}
</style>