{% extends 'base.html.twig' %}

{% block title %}Palace Canin | Nos chambres{% endblock %}


{% block css %}

    <link rel="stylesheet" href="{{ asset('assets/css/profil.css') }}">

{% endblock %}

{% block body %}



    <h1>PROFIL</h1>


    <h2>Bonjour <?= $pseudo ?></h2>

    <?= $content; // Affichage de la variable $content ?>

    
{% endblock %}