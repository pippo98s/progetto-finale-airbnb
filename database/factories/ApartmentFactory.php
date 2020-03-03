<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Apartment;
use Faker\Generator as Faker;


$factory->define(Apartment::class, function (Faker $faker) {


  $names = [
    'Appartamento al mare',
    'Appartamento in montagna',
    'Appartamento al lago',
    'Appartamento in città',
    'Villa sulla spiaggia',
    'Casa di campagna',
    'Baita sulla neve',
    'Trilocale con vista mare',
    'Monolocale nella foresta'
  ];
  $images = [
    'appartamento-1',
    'appartamento-2',
    'appartamento-3',
    'appartamento-4',
    'appartamento-5',
    'appartamento-6',
    'appartamento-7',
    'appartamento-8',
    'appartamento-9',
    'appartamento-10'
  ];

  $address = [
    "Via Roma",
    "Via C. Battist",
    "Piazza De Gasper",
    "via Erba",
    "Piazza Cavour",
    "Via Catanzaro",
    "Via Manzoni",
    "Via Municipio",
    "Via Marcora",
    "Largo Europa",
    "Piazza Italia",
    "Via Vitali",
    "Via Dante",
    "Via Marco Polo",
    "Via Crucicella",
    "Via Diaz",
    "Via Elena",
    "Corso Garibaldi",
    "Piazza Valarioti",
    "Via Palermo"
  ];

  $lat = [
    "38.675907",
    "39.297469",
    "37.526187",
    "38.116554",
    "40.723609",
    "39.223303",
    "40.352704",
    "41.111883",
    "40.626121",
    "40.851775",
    "41.892987",
    "42.460481",
    "42.954543",
    "43.935955",
    "45.463604",
    "45.068247",
    "45.376431",
    "43.770051",
    "45.436271",
    "38.112534"
  ];

  $lon = [
    "15.896210",
    "16.252990",
    "15.099913",
    "13.363579",
    "8.560860",
    "9.125121",
    "18.179860",
    "16.873494",
    "14.371972",
    "14.266565",
    "12.501931",
    "14.217714",
    "12.702062",
    "12.448517",
    "9.188477",
    "7.695826",
    "9.144908",
    "11.241944",
    "12.327576",
    "15.649649"
  ];

  $lat_lon = rand(0,19);

  return [
      'title' => $names[rand(0, 8)],
      'description' => $faker -> text($maxNbChars = 500),
      'price' => rand(500 , 100000) / 100,
      'rooms_number' => rand(1, 8),
      'guests_number'=> rand(1,8),
      'bathrooms' => rand(1,4),
      'area_sm'=> rand(15,500),
      'address_lat' => $lat[$lat_lon] ,
      'address_lon'=> $lon[$lat_lon],
      'address' => $address[rand(0,19)],
      'visibility' => 1,
      'image'=> 'apartments/' .$images[rand(0, 9)] .'.jpg'
  ];
});
