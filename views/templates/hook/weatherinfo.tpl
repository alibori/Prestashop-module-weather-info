<!-- Block weatherinfo -->
<div id="weatherinfo_block_home" class="block">
  <div class="block_content">
    <p>{$weather['name']}: {$weather['main']['temp']}ºC <img src="http://openweathermap.org/img/wn/{$weather['weather'][0]['icon']}.png" /> min {$weather['main']['temp_min']}ºC max {$weather['main']['temp_max']}ºC hum: {$weather['main']['humidity']}%</p>
  </div>
</div>
<!-- /Block weatherinfo -->