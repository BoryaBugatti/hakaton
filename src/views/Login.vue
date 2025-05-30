<script setup>
import { ref } from 'vue';
import axios from 'axios';
import router from '@/router';

const useremail = ref('');
const password = ref('');
const errorMessage = ref('');

const login = async () => {
  try {
    const response = await axios.post('http://localhost:9090/api/login/', {
      user_email: useremail.value,
      user_password: password.value,
    });
    
    const userdata = response.data;
    console.log('Data', userdata); 

    if (userdata && userdata.client_id) {
      localStorage.setItem('user', JSON.stringify(userdata));
      router.push({ path: `profile/${userdata.client_id}` });
    } else {
      errorMessage.value = 'Ошибка: данные пользователя отсутствуют';
      console.error('Ошибка: данные пользователя отсутствуют');
    }
  } catch (error) {
    errorMessage.value = 'Ошибка входа: ' + error.response.data.message || error.message;
    console.error('Ошибка входа:', error);
  }
};
</script>
<template>
  <div class="wrapper">
    <div class = "login-wrapper">
    
      <div class="login-form">
        <form @submit.prevent="login" class = "form-login">
          <h2 class = "title">Авторизация</h2>
            <p class="login-title">Ваш E-mail:</p>
            <input v-model="useremail" placeholder="Логин" class = "login-input" required />
            <div class="pass-titles">
              <p class="pass-title">Ваш пароль: </p>
              </div>
            <input type="password" v-model="password" placeholder="Пароль" required  class = "password-input"/>
            <button class = "sbmt-btn"type="submit">Войти</button>
            <div class="reg-block">
              <p class="reg-txt">У вас нет аккаунта?</p> 
              <a class = "reg-btn" href="/register">Зарегестрируйтесь!</a>
            </div>
            <p v-if="errorMessage" style="color: red;">{{ errorMessage }}</p>
        </form>
        
      </div>
      <hr class="line">
    </div>
    </div>
  </template>
  <style scoped>
.reg-btn:active{
  transform: scale(0.9);
}
.reg-btn:hover{
  transition: 0.2s ease;
  cursor: pointer;
  background-color: rgb(255, 255, 255);
  color: rgb(0, 135, 219);
  outline: 1px solid white;
}
.reg-btn{
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
.reg-block{
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 10px;
}
.reg-txt{
  color: white;
  font-family: "Noto Sans", sans-serif;
  font-size: 12px;
}
.line-up{
  margin-bottom: 20px;
  opacity: 0.2;
}
.line{
  margin-top: 30px;
  opacity: 0.2;
}
.login-title{
  color: white;
  font-family: "Noto Sans", sans-serif;
  max-height: 8px;
}
.pass-titles{
  
  display: flex;
  flex-direction: column;
  align-items: center;
}
.pass-title{
  max-height: 8px;
  color: white;
  font-family: "Noto Sans", sans-serif;
}
.joke-pswd{
  margin-top: -15px ;
  font-size: 10px;
  opacity: 0.1;
  color: white;
  font-family: "Noto Sans", sans-serif;
}
.reg-description{
  width: 500px;
  color: white;
  font-family: "Noto Sans", sans-serif;
}
.login-form{
  display: flex;
  flex-direction: column;
  margin-left: auto;
  margin-right: auto;
}

.card-image img{
  width: 200px;
  object-fit: cover;

}
.title{
  color: white;
  font-family: "Noto Sans", sans-serif;
}
.sbmt-btn:hover{
  cursor: pointer;
  background-color: rgb(0, 135, 219);
  outline: 1px solid white;
  color: white;
  transition: 0.1s ease;
}

.sbmt-btn:active{
  transform: scale(0.95);
}
.sbmt-btn{
  padding: 6px;
  border-radius: 16px;
  border: none;
  background-color: #ffffff;
  color: rgb(0, 135, 219);
  font-family: "Noto Sans", sans-serif;
  font-size: 16px;
  font-weight: 500;
  margin-top: 20px;
  }
  .password-input:focus{
    outline: 1px solid rgb(0, 135, 219);
  }
  .login-input:focus{
    outline: 1px solid rgb(0, 135, 219);

  }
  .password-input{
    background-color: #ffffff;
    width: 300px;
  color: rgb(48, 48, 48);
  padding: 8px;
  border: none;
  border-radius: 16px;
  outline: 1px solid rgb(0, 135, 219);
  margin-top: 10px;
  }
  .login-input{
    margin-top: 10px;
    background-color: #ffffff;
    width: 300px;
  color: rgb(48, 48, 48);
  padding: 8px;
  border: none;
  border-radius: 16px;
  outline: 1px solid #648845;
  }
  .form-login{
    min-height: 200px;
    width: 350px;
    padding: 8px;
    background-color: rgb(0, 135, 219);
    border-radius: 24px;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-left: auto;
    margin-right: auto;
    min-height: 300px;
  }
  .wrapper{
    width: 100vw;
    background-color: white;
    display: flex;
  }
  .login-wrapper{
    margin-left: auto;
    margin-right: auto;
    min-height: 30vw;
    width: 80vw;
    background-color: white;
  }
  @media screen and (max-width: 480px){
    .form-login{
        width: 300px;
    }
    .password-input{
        width: 280px;
    }
    .login-input{
        width: 280px;
    }
  }
</style>