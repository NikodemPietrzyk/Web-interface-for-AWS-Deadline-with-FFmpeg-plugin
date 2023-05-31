function validateResubmit(event){
    let condition = true;
    const form = event.target;
    event.preventDefault()
    const usedPreset = form.querySelector("[name=usedPreset]").value;
    const preset = form.querySelector("[name=preset]").value;

    const originalName = form.querySelector("[name=originalName]").value;
    const name = form.querySelector("[name=outputName]").value;
    let jobId = form.querySelector("[class=jobId]").innerText;
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


