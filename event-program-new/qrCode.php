<?php require('path.php') ?>
<style>
    body{
        background-color: rgb(241, 243, 246);
    }
    .center {
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
    }

    

    @media only screen and (min-width: 320px) {
        img {
        width: 100%;
        margin: auto;
    }
    
    }
    @media only screen and (min-width: 768px) {
        img {
        width: 70%;
        margin: auto;
    }
    
    }
    @media only screen and (min-width: 1440px) {
        img {
        width: 50%;
        margin: auto;
    }
    
    }
</style>
<div class="center">
    <img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=<?= $path . $_GET['id'] ?>" alt="ss">
</div>