(function() {
    // Check if the preload has already been done
    if (localStorage.getItem('preloadDone')) {
        var preloader = document.getElementById('preloader');
        preloader.style.display = 'none';
      return; // If so, do not run the preload again
    }
  
    var steps = generateRandomSteps();
    var currentStepIndex = 0;
    var totalDuration = 3000;
    var timePerStep = totalDuration / steps.length;
    
    var loaderElement = document.querySelector('.loader');
    var percentageElement = document.querySelector('.percentage');
    document.querySelector('body').classList.add('loading');
  
    // Function to generate random steps for the loader
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
  
    // Function to update the current step of the loader
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
  
    // Function to update the percentage display of the loader
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
  
    // Function to fade out and remove the preloader after completion
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
  
            // Set the flag in localStorage to indicate preload has been completed
            localStorage.setItem('preloadDone', 'true');
        }, 2000); // 2000ms to match the duration of the opacity transition
    }
  
    // Start updating the steps
    updateStep();
  })();
  