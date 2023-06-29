function validateResubmit(event){
    let condition = true;
    const form = event.target;
    event.preventDefault()
    const usedPreset = form.querySelector("[name=usedPreset]").value;
    const preset = form.querySelector("[name=preset]").value;

    const originalName = form.querySelector("[name=originalName]").value;
    const name = form.querySelector("[name=outputName]").value;
    let jobId = form.querySelector("[name=jobId]").value;
    console.log(preset);

    
    //jobId = jobId + "1511adsd"
    if(preset != usedPreset && preset == "custom"){
        return false
    }

    if(isValidFileName(name)){
        if(preset == usedPreset){
            if(name == originalName){
                requeueJob(jobId)
            }else{
                requeueJob(jobId, name)
            }
        }else{
            if(name == originalName){
                requeueJob(jobId, null, preset)
            }else{
                requeueJob(jobId, name, preset)
            }
        }
    }else{
        return false;
    }

    return true;
  
  }
  
  
var isValidFileName=(function(){
    var rg1=/^[^\\/:\*\?"<>\|]+$/; // forbidden characters \ / : * ? " < > |
    var rg2=/^\./; // cannot start with dot (.)
    var rg3=/^(nul|prn|con|lpt[0-9]|com[0-9])(\.|$)/i; // forbidden file names
    return function isValid(fname){
      return rg1.test(fname)&&!rg2.test(fname)&&!rg3.test(fname);
    }
})();



function requeueJob(jobId, name, preset){
    const ajax = new XMLHttpRequest()
    const method = "GET"
    let url
    if(!name && !preset){
        url = `src/jobSubmitter/jobSubmitter.php?jobId=${jobId}`;
    }else if(!preset && name){
        url = `src/jobSubmitter/jobSubmitter.php?jobId=${jobId}&name=${name}`;
    }else if(!name && preset){
        url = `src/jobSubmitter/jobSubmitter.php?jobId=${jobId}&preset=${preset}`;
    }else{
        url = `src/jobSubmitter/jobSubmitter.php?jobId=${jobId}&name=${name}&preset=${preset}`;
    }
    console.log(url)
    const asynchronous = true
    ajax.open(method,url,asynchronous)
    ajax.send();
    //const response = JSON.parse(this.responseText);
    ajax.onload = function ()
    {
        if(this.readyState == 4 && this.status == 200)
        {
            let data = this.responseText;
            console.log(data);
            alert("Submission succesfull"); 
            window.location.reload();
            return true;
        }else if(this.readyState == 4 && this.readyState == 404){
            console.log('Not found');
            alert("Submission failed"); 
        }else{
            console.log('weird error');
            alert("Submission failed"); 
            return false;
        }
    }
}


// Get modal elements
const modal = document.getElementById("videoModal");
const videoPlayer = document.getElementById("videoPlayer");
let isClearingSrc = false;  // Flag to indicate whether we're intentionally clearing the src

// Add click event listener to play button
document.querySelectorAll('.button').forEach(button => {
    button.addEventListener('click', function() {
        if (button.getAttribute("value") == "Play") {
            videoPlayer.src = button.dataset.video;  // Set the video source
            modal.style.display = "flex";  // Display the modal

            videoPlayer.onerror = function() {
                if (!isClearingSrc) {  // Only show the error message if we're not intentionally clearing the src
                    alert('Error: The video could not be loaded.');
                    modal.style.display = "none";  // Hide the modal
                }
            };
        }
    });
});

videoPlayer.addEventListener('loadeddata', function() {
    // Reset the flag once data is loaded successfully
    isClearingSrc = false;
});

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        isClearingSrc = true;  // Indicate that we're intentionally clearing the src
        videoPlayer.src = "";  // Reset the video src to stop the video
        modal.style.display = "none";
    }
}

