<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
{{--    <meta http-equiv="refresh" content="10">--}}
    <title>{{ config('app.name', 'TextBrew') }} - Maintenance</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
<div class="text-center">
    <img src="https://i.giphy.com/JliGmPEIgzGLe.webp" alt="Computer says no" class="mx-auto mb-4">
    <h1 class="text-4xl font-bold mb-4">{{ config('app.name', 'TextBrew') }} is getting an upgrade!</h1>
    <p class="text-xl mb-8">We're releasing new features and fixing bugs to enhance your experience. We'll be back shortly!</p>
{{--    <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-gray-900 mx-auto mb-4"></div>--}}
    <p class="text-lg">Refreshing in <span id="countdown">10</span> seconds</p>
</div>


<script>
    let countdown = 10;
    const countdownElement = document.getElementById('countdown');
    const timer = setInterval(() => {
        countdown--;
        countdownElement.textContent = countdown;

        if (countdown <= 0) {
            clearInterval(timer);
            location.reload();
        }
    }, 1000);
</script>

</body>
</html>
