(function() {
  var steps = generateRandomSteps();
  var currentStepIndex = 0;
  var totalDuration = 3000;
  var timePerStep = totalDuration / steps.length;
  
  var loaderElement = document.querySelector('.loader');
  var percentageElement = document.querySelector('.percentage');
  document.querySelector('body').classList.add('loading');

function generateRandomSteps() {
var randomSteps = [0];
var lastStep = 0;

for (var i = 0; i < 2; i++) { // We want 3 random steps + 0 and 100
    var maxStep = 100 - 15 * (3 - i); // Calculate the maximum possible value for the current step
    var nextStep = lastStep + 15 + Math.floor(Math.random() * (maxStep - lastStep - 15));
    randomSteps.push(nextStep);
    lastStep = nextStep;
}

randomSteps.push(100); // Always end at 100%
return randomSteps;
}


function updateStep() {
if (currentStepIndex < steps.length) {
    updatePercentage(steps[currentStepIndex]);
    currentStepIndex++;

    if (currentStepIndex < steps.length) {
        setTimeout(updateStep, timePerStep + (Math.random() * 200 - 100)); // Random delay between -100ms and +100ms
    }
} else {
    setTimeout(function() {
        document.getElementById('preloader').style.display = 'none';
    }, timePerStep); // Wait for the last animation to complete before hiding
}
}


function updatePercentage(percentage) {
    percentageElement.textContent = percentage + '%';
    var animation = loaderElement.animate([
        { width: getComputedStyle(loaderElement).width },
        { width: percentage + '%' }
    ], {
        duration: timePerStep,
        easing: 'cubic-bezier(0.785, 0.135, 0.15, 0.86)',
        fill: 'forwards'
    });

    animation.onfinish = function() {
        loaderElement.style.width = percentage + '%';
        if (percentage === 100) {
            fadeOutPreloader();
        }
    };
}

function fadeOutPreloader() {
    var preloader = document.getElementById('preloader');
    preloader.querySelector('.percentage').style.translate = '0% 100px';
    preloader.querySelector('.percentage').style.opacity = '0';
    preloader.querySelector('svg').style.opacity = '0';
    preloader.style.opacity = '0';
    document.querySelector('body').classList.add('loaded');
    
    // Wait for the opacity transition to complete, then set display: none
    setTimeout(function() {
        preloader.style.display = 'none';
        document.querySelector('body').classList.remove('loading');
    }, 2000); // 500ms matches the duration of the opacity transition in the CSS
}

  updateStep();
})();
