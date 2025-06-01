<script setup>
import axios from 'axios';
import { ref } from 'vue';

const username = ref('');
const password = ref('');
const email = ref('');
const errorMessage = ref('');

const register = async () => {
  try {
    const response = await axios.post('http://localhost:8090/api/registration', {
      user_name: username.value,
      user_password: password.value,
      user_email: email.value,
    });

    if (response.data.status === 'access') {
      alert('Вы успешно зарегистрировались!');
      // Очистка полей после успешной регистрации
      username.value = '';
      password.value = '';
      email.value = '';
    }
  } catch (error) {
    if (error.response) {
      // Ошибка от сервера
      errorMessage.value = error.response.data || 'Ошибка регистрации';
    } else {
      // Ошибка сети или другая ошибка
      errorMessage.value = 'Ошибка соединения с сервером';
    }
    console.error('Ошибка регистрации:', error);
  }
};
</script>
<template>
    <div class="cover">
        <img class = "background-image" src="../../public/background.png" alt="">
      </div>
    <div class="reg-wrapper">
        <div class="register">
        <form @submit.prevent="register" class = "form-login">
            <h2 class = "title">Регистрация</h2>
            <p class="login-title">Введите логин:</p>
            <input v-model="username" placeholder="Логин" class = "login-input" required />
            <div class="pass-titles">
                <p class="pass-title">Введите E-mail: </p>
            </div>
            <input type="email" v-model="email" placeholder="E-mail" required  class = "login-input"/> 
            
            <div class="pass-titles">
                <p class="pass-title">Введите пароль: </p>
            </div>
            <input type="password" v-model="password" placeholder="Пароль" required  class = "password-input"/> 
            <button class = "sbmt-btn" type="submit">Зарегистрироваться</button>
            <p v-if="errorMessage" style="color: red;">{{ errorMessage }}</p>
        </form>
        </div>
    </div>
</template>
<style scoped>

.upperline{
    margin-top: 20px;
    margin-bottom: 10px;
    opacity: 0.2;
}
.outlined{
    opacity: 0.2;
    margin-top: 20px;
}
.instance-info{
    
  color: white;
  font-family: "Noto Sans", sans-serif;
}
.pass-title{
    
  color: rgb(43, 43, 43);
  font-family: "Noto Sans", sans-serif;
}
.login-title{
  color: rgb(43, 43, 43);
  font-family: "Noto Sans", sans-serif;
}
.title{
  color: rgb(43, 43, 43);
  font-family: "Noto Sans", sans-serif;
}
.sbmt-btn{
    background-color: white;
    color: #161616;
    padding: 8px;
    border-radius: 16px;
    margin-top: 10px;
    border: none;
}
.sbmt-btn:hover{
    cursor: pointer;
    background-color: #ffffff;
    color: rgb(43, 43, 43);
    outline: 1px solid white;
    transition: 0.2s ease;
}
.background-image{
  position: absolute;
  top: 0;
  left: 0;
  z-index: -1;
  object-fit: cover;
  width: 100vw;
  height: 100%;
  object-fit: cover;
  transform: rotate(180deg);
}
.sbmt-btn:active
{
    transform: scale(0.95);
}

.form-login{
    display: flex;
    flex-direction: column;
    align-items: center;
    
    
}
.info{
    display: flex;
    flex-direction: column;
    align-items: center;
}
input{
    padding: 8px;
    border: none;
    border-radius: 16px;
    background-color: #ffffff;
    color: rgb(38, 38, 38);
    outline: 1px solid rgb(0, 135, 219);
    width: 250px;
}
input:focus{
    outline: 1px solid #aeaeae;
}
    .reg-wrapper{
        width: 100vw;
        display: flex;
        background-color: #ffffff;
    }
    .register{
        width: 80vw;
        margin-left: auto;
        margin-right: auto;
    }

</style>