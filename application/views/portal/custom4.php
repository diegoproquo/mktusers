<style>
  body {
    font-family: "Open Sans", sans-serif;
    margin: 0;
    height: 100vh;
    display: grid;
    place-items: center;
    background-color: #25a3ff;
    position: relative;
    overflow: hidden;
  }

  .c-checkbox {
    display: none;
  }

  .c-checkbox:nth-of-type(1):checked~.c-formContainer .c-form__toggle {
    visibility: hidden;
    opacity: 0;
    transform: scale(0.7);
  }

  .c-checkbox:nth-of-type(1):checked~.c-formContainer .c-form {
    width: 382px;
  }

  .c-checkbox:nth-of-type(1):checked~.c-formContainer .c-form .c-form__group:nth-child(1) {
    visibility: visible;
    opacity: 1;
  }

  .c-checkbox:nth-of-type(1):checked~.c-formContainer .c-form .c-form__group:nth-child(1) .c-form__border {
    width: 100%;
  }

  .c-checkbox:nth-of-type(1):checked~.c-formContainer .c-form .c-form__group:nth-child(1) .c-form__input:not(:placeholder-shown):invalid~.c-form__border,
  .c-checkbox:nth-of-type(1):checked~.c-formContainer .c-form .c-form__group:nth-child(1) .c-form__input:not(:placeholder-shown):invalid~.c-form__next {
    color: #ff0033;
  }

  .c-checkbox:nth-of-type(1):checked~.c-formContainer .c-form .c-form__group:nth-child(1) .c-form__input:required:valid~.c-form__next {
    pointer-events: initial;
    color: #565656;
  }

  .c-checkbox:nth-of-type(2):checked~.c-form__progress {
    width: calc(100vw / 3);
  }

  .c-checkbox:nth-of-type(2):checked~.c-formContainer .c-form .c-form__group:nth-child(1) {
    visibility: hidden;
    opacity: 0;
  }

  .c-checkbox:nth-of-type(2):checked~.c-formContainer .c-form .c-form__group:nth-child(1) .c-form__border {
    width: 0;
  }

  .c-checkbox:nth-of-type(2):checked~.c-formContainer .c-form .c-form__group:nth-child(2) {
    visibility: visible;
    opacity: 1;
  }

  .c-checkbox:nth-of-type(2):checked~.c-formContainer .c-form .c-form__group:nth-child(2) .c-form__border {
    width: 100%;
  }

  .c-checkbox:nth-of-type(2):checked~.c-formContainer .c-form .c-form__group:nth-child(2) .c-form__input:not(:placeholder-shown):invalid~.c-form__border,
  .c-checkbox:nth-of-type(2):checked~.c-formContainer .c-form .c-form__group:nth-child(2) .c-form__input:not(:placeholder-shown):invalid~.c-form__next {
    color: #ff0033;
  }

  .c-checkbox:nth-of-type(2):checked~.c-formContainer .c-form .c-form__group:nth-child(2) .c-form__input:required:valid~.c-form__next {
    pointer-events: initial;
    color: #565656;
  }

  .c-checkbox:nth-of-type(3):checked~.c-form__progress {
    width: calc((100vw / 3) + 100vw / 3);
  }

  .c-checkbox:nth-of-type(3):checked~.c-formContainer .c-form .c-form__group:nth-child(2) {
    visibility: hidden;
    opacity: 0;
  }

  .c-checkbox:nth-of-type(3):checked~.c-formContainer .c-form .c-form__group:nth-child(2) .c-form__border {
    width: 0;
  }

  .c-checkbox:nth-of-type(3):checked~.c-formContainer .c-form .c-form__group:nth-child(3) {
    visibility: visible;
    opacity: 1;
  }

  .c-checkbox:nth-of-type(3):checked~.c-formContainer .c-form .c-form__group:nth-child(3) .c-form__border {
    width: 100%;
  }

  .c-checkbox:nth-of-type(3):checked~.c-formContainer .c-form .c-form__group:nth-child(3) .c-form__input:not(:placeholder-shown):invalid~.c-form__border,
  .c-checkbox:nth-of-type(3):checked~.c-formContainer .c-form .c-form__group:nth-child(3) .c-form__input:not(:placeholder-shown):invalid~.c-form__next {
    color: #ff0033;
  }

  .c-checkbox:nth-of-type(3):checked~.c-formContainer .c-form .c-form__group:nth-child(3) .c-form__input:required:valid~.c-form__next {
    pointer-events: initial;
    color: #565656;
  }

  #finish:checked~.c-form__progress {
    width: 100vw;
  }

  #finish:checked~.c-formContainer .c-form {
    transition: opacity 0.2s 0.1s, transform 0.3s;
    opacity: 0;
    transform: translateX(50%) scaleX(0);
  }

  #finish:checked~.c-formContainer .c-form .c-form__group:nth-child(3) {
    transition: 0s;
    visibility: hidden;
    opacity: 0;
  }

  #finish:checked~.c-formContainer .c-form .c-form__group:nth-child(3) .c-form__border {
    width: 0;
  }

  #finish:checked~.c-formContainer .c-welcome {
    opacity: 1;
  }

  .c-formContainer {
    width: 180px;
    height: 65px;
    z-index: 1;
    display: flex;
    justify-content: center;
  }

  .c-welcome {
    position: absolute;
    width: max-content;
    height: inherit;
    font-size: 40px;
    color: #ffffff;
    opacity: 0;
    transition: 0.3s;
  }

  .c-form {
    position: relative;
    width: inherit;
    height: inherit;
    background-color: #ffffff;
    box-shadow: 0 5px 10px -2px rgba(0, 0, 0, 0.2), 0 2px 4px -1px rgba(0, 0, 0, 0.3);
  }

  .c-form__toggle {
    width: inherit;
    height: inherit;
    font-size: 18px;
    color: #333333;
    cursor: pointer;
    transition: 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .c-form__toggleIcon {
    display: inline-block;
    margin-left: 10px;
    position: relative;
    width: 15px;
    height: 17px;
  }

  .c-form__toggleIcon::before,
  .c-form__toggleIcon::after {
    content: "";
    position: absolute;
    background-color: #565656;
  }

  .c-form__toggleIcon::before {
    left: 50%;
    transform: translateX(-50%);
    width: 9px;
    height: 9px;
    border-radius: 50%;
  }

  .c-form__toggleIcon::after {
    bottom: 0;
    width: 100%;
    height: 7px;
    border-radius: 7px 7px 0 0;
  }

  .c-form__group {
    width: 100%;
    height: 100%;
    visibility: hidden;
    opacity: 0;
    padding: 12px 15px;
    box-sizing: border-box;
    transition: 0.2s 0.2s;
    position: absolute;
  }

  .c-form__label {
    position: relative;
    cursor: pointer;
    width: calc(100% - 40px);
    height: 100%;
    display: flex;
    align-items: flex-end;
  }

  .c-form__input {
    font-size: 20px;
    font-family: inherit;
    width: 100%;
    height: 90%;
    border: 0;
    outline: 0;
    color: #333333;
    box-sizing: border-box;
    cursor: pointer;
  }

  .c-form__input:focus~.c-form__groupLabel,
  .c-form__input:not(:placeholder-shown)~.c-form__groupLabel {
    font-size: 10px;
    top: -4px;
    transform: translateY(0);
    color: #333333;
  }

  .c-form__input:focus~.c-form__border,
  .c-form__input:focus~.c-form__next,
  .c-form__input:not(:placeholder-shown)~.c-form__border,
  .c-form__input:not(:placeholder-shown)~.c-form__next {
    transition: 0.3s;
  }

  .c-form__input:focus {
    cursor: initial;
  }

  .c-form__groupLabel {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    transition: 0.2s;
  }

  .c-form__border {
    position: absolute;
    width: 0;
    height: 3px;
    color: #25a3ff;
    background-color: currentColor;
    transition: 1s 0.2s ease-out;
  }

  .c-form__next {
    color: #25a3ff;
    position: absolute;
    right: -40px;
    height: 100%;
    width: 40px;
    cursor: pointer;
    pointer-events: none;
    display: flex;
    align-items: center;
    justify-content: flex-end;
  }

  .c-form__nextIcon {
    position: relative;
    right: 1.5px;
    width: 20px;
    height: 3px;
    background-color: currentColor;
  }

  .c-form__nextIcon::before,
  .c-form__nextIcon::after {
    content: "";
    position: absolute;
    right: -1.5px;
    width: 15px;
    height: 3px;
    background-color: inherit;
  }

  .c-form__nextIcon::before {
    bottom: -1.5px;
    transform-origin: top right;
    transform: rotate(45deg);
  }

  .c-form__nextIcon::after {
    top: -1.5px;
    transform-origin: bottom right;
    transform: rotate(-45deg);
  }

  .c-form__progress {
    position: fixed;
    left: 0;
    height: 100%;
    width: 0;
    background-color: red;
    transition: 0.3s;
  }
</style>




<input class="c-checkbox" type="checkbox" id="start">
<input class="c-checkbox" type="checkbox" id="progress2">
<input class="c-checkbox" type="checkbox" id="progress3">
<input class="c-checkbox" type="checkbox" id="finish">
<div class="c-form__progress"></div>

<div class="c-formContainer">
  <div class="c-welcome">Welcome aboard!</div>
  <form class="c-form" action="">
    <div class="c-form__group">
      <label class="c-form__label" for="username">
        <input type="text" id="username" class="c-form__input" placeholder=" " pattern="[^\s]+" required>

        <label class="c-form__next" for="progress2" role="button">
          <span class="c-form__nextIcon"></span>
        </label>

        <span class="c-form__groupLabel">Create your username.</span>
        <b class="c-form__border"></b>
      </label>
    </div>

    <div class="c-form__group">
      <label class="c-form__label" for="femail">
        <input type="email" id="femail" class="c-form__input" placeholder=" " pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{1,63}$" required>

        <label class="c-form__next" for="progress3" role="button">
          <span class="c-form__nextIcon"></span>
        </label>

        <span class="c-form__groupLabel">What's your email?</span>
        <b class="c-form__border"></b>
      </label>
    </div>

    <div class="c-form__group">
      <label class="c-form__label" for="fpass">
        <input type="password" id="fpass" class="c-form__input" placeholder=" " required>

        <label class="c-form__next" for="finish" role="button">
          <span class="c-form__nextIcon"></span>
        </label>

        <span class="c-form__groupLabel">Create your password.</span>
        <b class="c-form__border"></b>
      </label>
    </div>

    <label class="c-form__toggle" for="start">Register<span class="c-form__toggleIcon"></span></label>
  </form>
</div>