function getPreset(url){
  const ajax = new XMLHttpRequest()
  const method = "GET"
  const asynchronous = false
  ajax.open(method,url,asynchronous)
  ajax.send()
  return JSON.parse(ajax.responseText)
}
const globalPresets = getPreset("src/jobSubmitter/getPreset.php?global=1&user=0")
const userPresets = getPreset("src/jobSubmitter/getPreset.php?global=0&user=1")
//turns off enter on whole page in order to not submit form on accident.
window.addEventListener('keydown',function(e){if(e.keyIdentifier=='U+000A'||e.keyIdentifier=='Enter'||e.keyCode==13){if(e.target.nodeName=='INPUT'&&e.target.type=='text'){e.preventDefault();return false;}}},true);
const duplicateJobButton = document.getElementById("duplicateJob")
const removeJobButton = document.getElementById("removeJob")
const savePresetButton = document.getElementById("savePreset")


if(!localStorage.getItem('files')){
  alert("empty storage")
  window.location.replace("/directory");
}else if(localStorage.getItem('files') == "[]"){
  alert("empty storage")
  window.location.replace("/directory");
}


var jobSavedState = false

if(localStorage.getItem("jobs")){
  jobSavedState = true
}


const onDeleteBtnClick = (parent) => {
  console.log(parent)
  let jobId = false;
  if(parent.parentNode.getAttribute('data-job-id')){
    jobId = parent.parentNode.getAttribute('data-job-id');
  }
  parent.removeChild(parent.firstChild)
  parent.removeChild(parent.firstChild)
  if(jobId){
    updateJobLocalStorage(jobId)
  }
}

const addDeleteIcon = (parent) => {
  const deleteIcon = document.createElement("div");
  deleteIcon.addEventListener("click", () => onDeleteBtnClick(parent));
  deleteIcon.innerText = "X";
  deleteIcon.className = "delete";
  parent.appendChild(deleteIcon);
}


fillSourceBox()
const draggables = document.querySelectorAll('[class^=draggable]')

let jobContainer = document.getElementById("jobContainer")
const add = document.getElementById("add");

var parent = false
var value = 15

add.onclick = function() {
    addJobDiv()   
}


function addContainerVideoDiv(jobDiv, videoFile){
  const containerVideoDiv = document.createElement("div")
  containerVideoDiv.className = "containerVideo"
  jobDiv.appendChild(containerVideoDiv)
  createContainerHangler(containerVideoDiv)

}

function addContainerAudioDiv(jobDiv, audioFile){
  const containerAudioDiv = document.createElement("div")
  containerAudioDiv.className = "containerAudio"
  jobDiv.appendChild(containerAudioDiv)
  createContainerHangler(containerAudioDiv)
}

function addCodecDropDownDivDiv(jobDiv, codecValue){
  const codecDropDownDiv = document.createElement("select")
  codecDropDownDiv.className = "codecDropDown"
  codecDropDownDiv.disabled = true
  codecDropDownDiv.name = "codec[]";
  //codecValue = "h.264"
  codecDropDownDiv.innerHTML = `<option value="H.264" >H.264</option>
                                <option value="test">Test</option>`
  if(codecValue){codecDropDownDiv.value = codecValue}
  jobDiv.appendChild(codecDropDownDiv)
  //createContainerHangler(containerAudioDiv)
}

function addContDropDownDivDiv(jobDiv, contValue){
  const contDropDownDiv = document.createElement("select")
  contDropDownDiv.disabled = true
  contDropDownDiv.className = "contDropDown"
  contDropDownDiv.name = "container[]";
  contDropDownDiv.innerHTML = `<option value="mp4" >MP4</option>
                               <option value="mov">MOV</option>`
  if(contValue){contDropDownDiv.value = contValue}
  jobDiv.appendChild(contDropDownDiv)
  //createContainerHangler(containerAudioDiv)
}

function addPresetDropDownDiv(jobDiv, presetValue){
  const presetDropDownDiv = document.createElement("select")
  presetDropDownDiv.className = "presetDropDown"
  presetDropDownDiv.innerHTML = `<option>Custom</option>` 
  presetDropDownDiv.name = "presetId[]";
  for (let i in userPresets){
    presetDropDownDiv.innerHTML = presetDropDownDiv.innerHTML + `<option value=${userPresets[i]["id"]}>${userPresets[i]["name"]}</option>`  
  }     
  for (let i in globalPresets){
    presetDropDownDiv.innerHTML = presetDropDownDiv.innerHTML + `<option value=${globalPresets[i]["id"]}>${globalPresets[i]["name"]}</option>`  
  }                     
  if(presetValue){presetDropDownDiv.value = presetValue}
  jobDiv.appendChild(presetDropDownDiv)
  return presetDropDownDiv
}


function addResolutionWidthInput(jobDiv, resolutionWidth){
  const resolutionWidthInput = document.createElement("input")
  resolutionWidthInput.type = "number"
  resolutionWidthInput.className = "resolutionWidthInput";
  resolutionWidthInput.placeholder = "width";
  resolutionWidthInput.setAttribute('min',1);
  resolutionWidthInput.setAttribute('max',4092);
  resolutionWidthInput.name = "resolutionWidth[]";
  resolutionWidthInput.value = resolutionWidth
  jobDiv.appendChild(resolutionWidthInput)
  return resolutionWidthInput
}


function addResolutionHeightInput(jobDiv, resolutionHeight){
  const resolutionHeightInput = document.createElement("input")
  resolutionHeightInput.type = "number"
  resolutionHeightInput.className = "resolutionHeightInput"
  resolutionHeightInput.placeholder = "height"
  resolutionHeightInput.setAttribute('min',1);
  resolutionHeightInput.setAttribute('max',2160);
  resolutionHeightInput.name = "resolutionHeight[]"
  resolutionHeightInput.value = resolutionHeight
  jobDiv.appendChild(resolutionHeightInput)
  return resolutionHeightInput
}

function addBitrateInput(jobDiv, bitrateValue){
  const bitrateInput = document.createElement("input")
  bitrateInput.type = "number"
  bitrateInput.placeholder = "bitrate (kbps)"
  bitrateInput.className = "bitrateInput"
  bitrateInput.setAttribute('min',100);
  bitrateInput.setAttribute('max',50000);
  bitrateInput.name = "bitrate[]"
  bitrateInput.value = bitrateValue
  jobDiv.appendChild(bitrateInput)
  return bitrateInput
}

function addFrameRateDropDownDiv(jobDiv, frameRateValue){
  const frameRateDropDownDiv = document.createElement("select")
  frameRateDropDownDiv.name = "frameRate[]"
  frameRateDropDownDiv.className = "framerateDropDown"
  frameRateDropDownDiv.innerHTML = `<option class="optionLi">25</option>
                                    <option class="optionLi">24</option>
                                    <option class="optionLi">29.97</option>
                                    <option class="optionLi">23.976</option>
                                    <option class="optionLi">60</option>
                                    <option class="optionLi">30</option>`                                    
  if(frameRateValue){frameRateDropDownDiv.value = frameRateValue}
  jobDiv.appendChild(frameRateDropDownDiv)
  return frameRateDropDownDiv
}


function addAudioCheckbox(jobDiv, isChecked){
  const audioCheckbox = document.createElement("input")
  audioCheckbox.type = "checkbox"
  audioCheckbox.checked = true
  if (isChecked == false) {audioCheckbox.checked = false}
  audioCheckbox.className = "audioCheckbox"
  //audioCheckbox.name = "audio[]"
  jobDiv.appendChild(audioCheckbox)
  return audioCheckbox
}


function addAudioBitrateDropDownDiv(jobDiv, audioBitrateValue){
  const audioBitrateDropDownDiv = document.createElement("select")
  audioBitrateDropDownDiv.name = "audioBitrate[]"
  audioBitrateDropDownDiv.className = "audioBitrateDropDown"
  audioBitrateDropDownDiv.innerHTML =  `<option>56</option>
                                        <option>96</option>
                                        <option>128</option>
                                        <option>160</option>
                                        <option>192</option>
                                        <option>224</option>
                                        <option>256</option>
                                        <option>288</option>
                                        <option>320</option>`
  audioBitrateDropDownDiv.value = 320
  if(audioBitrateValue){audioBitrateDropDownDiv.value = audioBitrateValue}
  jobDiv.appendChild(audioBitrateDropDownDiv)
  return audioBitrateDropDownDiv
}


function addSendMailCheckbox(jobDiv, isChecked){
  const sendMailCheckbox = document.createElement("input")
  sendMailCheckbox.type = "checkbox"
  sendMailCheckbox.checked = false
  if (isChecked == true) {sendMailCheckbox.checked = true}
  sendMailCheckbox.className = "sendMailCheckbox"
  //sendMailCheckbox.name = "mail[]"
  jobDiv.appendChild(sendMailCheckbox)
  return sendMailCheckbox
}

function addOutputNameInput(jobDiv, outputName){
  const outputNameInput = document.createElement("input")
  outputNameInput.type="text"
  outputNameInput.className = "outputNameInput"
  outputNameInput.name = "outputName[]"
  if(outputName){outputNameInput.value = outputName}
  jobDiv.appendChild(outputNameInput);
  return outputNameInput
}



function addJobDiv(videoFile, audioFile, codecValue, contValue, presetValue, resolutionWidth, resolutionHeight, bitrateValue, frameRateValue, isCheckedAudio, audioBitrateValue, isCheckedMail, nextChild, jobIdRestored, outputName) {
  const jobContainer = document.getElementById("jobContainer")
  const jobDiv = document.createElement("div")
    jobDiv.className = "job"

    addContainerVideoDiv(jobDiv)
    addContainerAudioDiv(jobDiv)
    addCodecDropDownDivDiv(jobDiv, codecValue)
    addContDropDownDivDiv(jobDiv, contValue)
    const presetDropDownDiv = addPresetDropDownDiv(jobDiv, presetValue)
    const resolutionWidthInput = addResolutionWidthInput(jobDiv, resolutionWidth)
    const resolutionHeightInput = addResolutionHeightInput(jobDiv, resolutionHeight)
    const bitrateInput = addBitrateInput(jobDiv, bitrateValue)
    const frameRateDropDown = addFrameRateDropDownDiv(jobDiv, frameRateValue)
    const audioCheckbox = addAudioCheckbox(jobDiv, isCheckedAudio)
    const audioBitrateDropDown = addAudioBitrateDropDownDiv(jobDiv, audioBitrateValue)
    const sendMailCheckbox = addSendMailCheckbox(jobDiv, isCheckedMail)
    const outputNameInput = addOutputNameInput(jobDiv, outputName)
    
    // addChangeListener(checkbox)

    // const outputNameInput = document.createElement("input")
    // //outputNameInput.type = "input"
    // outputNameInput.type="text"
    // outputNameInput.className = "outputNameInput"
    // outputNameInput.name = "outputName[]"
    
    


    // jobDiv.appendChild(outputNameInput);
    if(videoFile){
      parent = jobDiv.querySelector("[class=containerVideo]")
      copyNode(videoFile)
      }
  
      if(audioFile){
      parent = jobDiv.querySelector("[class=containerAudio]")
      copyNode(audioFile)
      }



    if (nextChild){
      jobContainer.insertBefore(jobDiv,nextChild)
    }else{
      jobContainer.appendChild(jobDiv)
    }

    if(!jobIdRestored){
      updateOrder('right')
      saveAddJobDiv(jobDiv)
    }else{
      jobDiv.dataset.jobId = jobIdRestored
    }
    
    
    outputNameInput.addEventListener('input', () => {
      const jobId = outputNameInput.parentNode.getAttribute('data-job-id');
      updateJobLocalStorage(jobId)
    })


    createPresetDropDownHandler(presetDropDownDiv)
    createInputValidator(resolutionWidthInput)
    createInputValidator(resolutionHeightInput)
    createInputValidator(bitrateInput)

    addChangeListener(audioCheckbox)
    addChangeListener(sendMailCheckbox)
    addChangeListener(frameRateDropDown)
    addChangeListener(audioBitrateDropDown)

    createContextMenuHandler(jobDiv)

    

}


draggables.forEach(draggable => {
  draggable.addEventListener('dragstart', () => {
    highlightContainer(1,draggable)
    draggable.classList.add('dragging')    
  })
  draggable.addEventListener('dragend', () => {
    copyNode (draggable)
    highlightContainer(0,draggable)
    draggable.classList.remove('dragging')
  })
})


if(jobSavedState){
  restoreJobContainer();
}


function highlightContainer(isOn,draggable) {
  const containers = document.querySelectorAll('[class^=container]')
  containers.forEach(container => {
    if (container.classList[0].endsWith("Video") && draggable.classList[0].endsWith("Video") && isOn) {
    container.classList.add('highlight')
    }else if(container.classList[0].endsWith("Audio") && draggable.classList[0].endsWith("Audio") && isOn){
    container.classList.add('highlight')
    }else{
    container.classList.remove('highlight')
    }
  })
}


function copyNode (draggable) {
  if(parent == false){
    return
  }
  if ((parent.classList[0].endsWith("Video") && draggable.classList[0].endsWith("Video")) || (parent.classList[0].endsWith("Audio") && draggable.classList[0].endsWith("Audio"))){
    if(parent.firstChild){
      parent.removeChild(parent.firstChild)
      parent.removeChild(parent.firstChild)
    }
    const copiedNode = draggable.cloneNode(true)
    copiedNode.classList.remove('dragging')
    parent.appendChild(copiedNode)
    copiedNode.setAttribute('draggable',false);
    if(parent.classList[0].endsWith("Video")){
    copiedNode.className = "nonDraggableVideo"
    }
    if(parent.classList[0].endsWith("Audio")){
    copiedNode.className = "nonDraggableAudio"
    //copiedNode.innerHTML += `<input type="hidden" name="audioFile[]" value="${copiedNode.innerText}"></input>`
    }
    if(parent.parentNode.getAttribute('data-job-id')){
      const jobId = parent.parentNode.getAttribute('data-job-id');
      updateJobLocalStorage(jobId)
    }
    if (!parent.parentElement.querySelector('[class=outputNameInput]').value){
    parent.parentElement.querySelector('[class=outputNameInput]').value = copiedNode.innerText.slice(0, -4)
    }
    addDeleteIcon(parent)
  }
  parent = false
}



function fillBasedOnPreset (value,node){
  for (let i in globalPresets){
    if (globalPresets[i]["id"] == value){
      if(globalPresets[i]["width"]){node.querySelector('[class=resolutionWidthInput]').value = globalPresets[i]["width"] }
      if(globalPresets[i]["height"]){node.querySelector('[class=resolutionHeightInput]').value = globalPresets[i]["height"]}
      if(globalPresets[i]["bitrate"]){node.querySelector('[class=bitrateInput]').value = globalPresets[i]["bitrate"]}
      if(globalPresets[i]["framerate"]){node.querySelector('[class=framerateDropDown]').value = globalPresets[i]["framerate"]}
      if(globalPresets[i]["audio"]=="1"){node.querySelector('[class=audioCheckbox]').checked = true} else node.querySelector('[class=audioCheckbox]').checked = false
      if(globalPresets[i]["audio_bitrate"]){node.querySelector('[class=audioBitrateDropDown]').value = globalPresets[i]["audio_bitrate"]}
      if(globalPresets[i]["send_email"]=="1"){node.querySelector('[class=sendMailCheckbox]').checked = true} else node.querySelector('[class=sendMailCheckbox]').checked = false
    }
  }
  for (let i in userPresets){
    if (userPresets[i]["id"] == value){
      if(userPresets[i]["width"]){node.querySelector('[class=resolutionWidthInput]').value = userPresets[i]["width"] }
      if(userPresets[i]["height"]){node.querySelector('[class=resolutionHeightInput]').value = userPresets[i]["height"]}
      if(userPresets[i]["bitrate"]){node.querySelector('[class=bitrateInput]').value = userPresets[i]["bitrate"]}
      if(userPresets[i]["framerate"]){node.querySelector('[class=framerateDropDown]').value = userPresets[i]["framerate"]}
      if(userPresets[i]["audio"]=="1"){node.querySelector('[class=audioCheckbox]').checked = true} else node.querySelector('[class=audioCheckbox]').checked = false
      if(userPresets[i]["audio_bitrate"]){node.querySelector('[class=audioBitrateDropDown]').value = userPresets[i]["audio_bitrate"]}
      if(userPresets[i]["send_email"]=="1"){node.querySelector('[class=sendMailCheckbox]').checked = true} else node.querySelector('[class=sendMailCheckbox]').checked = false
    }
  }
}

function createPresetDropDownHandler (presetDropDown){
  presetDropDown.addEventListener('change', () => {
    fillBasedOnPreset(event.target.value,presetDropDown.parentNode)
    const jobId = presetDropDown.parentNode.getAttribute('data-job-id');
    updateJobLocalStorage(jobId)
  })
}


function createInputValidator(input){
  input.addEventListener('input', () => {
    input.parentNode.querySelector('[class=presetDropDown]').value = "Custom"
    const jobId = input.parentNode.getAttribute('data-job-id');
    updateJobLocalStorage(jobId)
  })
}

function addChangeListener(checkbox){
  checkbox.addEventListener('change', function() {
    checkbox.parentNode.querySelector('[class=presetDropDown]').value = "Custom"
    const jobId = checkbox.parentNode.getAttribute('data-job-id');
    updateJobLocalStorage(jobId)
  })
}



function createContainerHangler (container){
  container.addEventListener("dragover", e => {
    e.preventDefault()
    parent=container;
  })
  container.addEventListener("dragleave", () => {
    parent=false;
  })
}

function fillSourceBox (){

  const fileList = JSON.parse(localStorage.getItem('files'))
  const boxVideo = document.querySelector('[class=boxVideo]')
  const boxAudio = document.querySelector('[class=boxAudio]')

  for (let data of fileList) {
    if(isVideo(data)){
      let draggableVideo = document.createElement("div")
      draggableVideo.className = "draggableVideo"
      draggableVideo.setAttribute('draggable',true)
      draggableVideo.innerText = data.split("/").pop()
      draggableVideo.innerHTML += `<input type="hidden" name="videoFile[]" value="${data}"></input>`
      boxVideo.appendChild(draggableVideo)
      if(!jobSavedState){addJobDiv(draggableVideo)}
    }else if (isAudio(data)){
      let draggableAudio = document.createElement("div")
      draggableAudio.className = "draggableAudio"
      draggableAudio.setAttribute('draggable',true)
      draggableAudio.innerText = data.split("/").pop()
      draggableAudio.innerHTML += `<input type="hidden" name="audioFile[]" value="${data}"></input>`
      boxAudio.appendChild(draggableAudio)
    }
  }
}

function isVideo (str){
  if (str.endsWith(".dpx")){return true}
  if (str.endsWith(".mp4")){return true}
  if (str.endsWith(".mov")){return true}
  if (str.endsWith(".mxf")){return true}

  return false
}
function isAudio (str){
  if (str.endsWith(".wav")){return true}
  if (str.endsWith(".aac")){return true}
  if (str.endsWith(".mp3")){return true}

  return false
}




var contextJob = false

function createContextMenuHandler(jobDiv){
  jobDiv.addEventListener("contextmenu", function(e){
    e.preventDefault();
    let contextElement = document.getElementById("context-menu")
    contextElement.style.top = e.clientY +"px"
    contextElement.style.left = e.clientX + "px"
    contextElement.classList.add("active")
    contextJob = jobDiv
})
}

duplicateJobButton.addEventListener("click", function(){
  addJobDiv(contextJob.querySelector("[class=nonDraggableVideo]"),
            contextJob.querySelector("[class=nonDraggableAudio]"),
            contextJob.querySelector("[class=codecDropDown]").value,
            contextJob.querySelector("[class=contDropDown]").value,
            contextJob.querySelector("[class=presetDropDown]").value,
            contextJob.querySelector("[class=resolutionWidthInput]").value,
            contextJob.querySelector("[class=resolutionHeightInput]").value,
            contextJob.querySelector("[class=bitrateInput]").value,
            contextJob.querySelector("[class=framerateDropDown]").value,
            contextJob.querySelector("[class=audioCheckbox]").checked,
            contextJob.querySelector("[class=audioBitrateDropDown]").value,
            contextJob.querySelector("[class=sendMailCheckbox]").checked,
            contextJob.nextSibling,
            null,
            contextJob.querySelector('[class=outputNameInput]').value ? contextJob.querySelector('[class=outputNameInput]').value +'_1' : null,
            )
})
var savePresetUrl = "src/jobSubmitter/saveUserPreset.php?";
savePresetButton.addEventListener("click", function(){
  savePresetUrl = "src/jobSubmitter/saveUserPreset.php?";
  if(contextJob.querySelector("[class=codecDropDown]").value){
    savePresetUrl = savePresetUrl + "codec=" +  contextJob.querySelector("[class=codecDropDown]").value + "&"
    console.log(contextJob.querySelector("[class=codecDropDown]").value)
  }
  if(contextJob.querySelector("[class=resolutionWidthInput]").value){
    savePresetUrl = savePresetUrl + "width=" + contextJob.querySelector("[class=resolutionWidthInput]").value + "&"
    console.log(contextJob.querySelector("[class=resolutionWidthInput]").value)
  }
  if(contextJob.querySelector("[class=resolutionHeightInput]").value){
    savePresetUrl = savePresetUrl + "height=" + contextJob.querySelector("[class=resolutionHeightInput]").value + "&"
    console.log(contextJob.querySelector("[class=resolutionHeightInput]").value)
  }
  if(contextJob.querySelector("[class=bitrateInput]").value){
    savePresetUrl = savePresetUrl + "bitrate=" + contextJob.querySelector("[class=bitrateInput]").value + "&"
    console.log(contextJob.querySelector("[class=bitrateInput]").value)
  }
  if(contextJob.querySelector("[class=framerateDropDown]").value){
    savePresetUrl = savePresetUrl + "framerate=" + contextJob.querySelector("[class=framerateDropDown]").value + "&"
    console.log(contextJob.querySelector("[class=framerateDropDown]").value)
  }
  if(contextJob.querySelector("[class=audioCheckbox]").checked){
    savePresetUrl = savePresetUrl + "audio=" + contextJob.querySelector("[class=audioCheckbox]").checked + "&"
    console.log(contextJob.querySelector("[class=audioCheckbox]").checked)
  }
  if(contextJob.querySelector("[class=audioBitrateDropDown]").value){
    savePresetUrl = savePresetUrl + "audioBitrate=" + contextJob.querySelector("[class=audioBitrateDropDown]").value + "&"
    console.log(contextJob.querySelector("[class=audioBitrateDropDown]").value)
  }
  if(contextJob.querySelector("[class=sendMailCheckbox]").checked){
    savePresetUrl = savePresetUrl + "mail=" + contextJob.querySelector("[class=sendMailCheckbox]").checked +"&"
    console.log(contextJob.querySelector("[class=sendMailCheckbox]").checked)
  }

  
  modalPreset.style.display = "block";
  
  console.log(savePresetUrl)
})

const modalPreset = document.getElementById("presetModal");
const presetSubmit = document.getElementById('presetButtonSubmit')

window.onclick = function(event) {
  if (event.target == modalPreset) {
    modalPreset.style.display = "none";
  }
} 

presetSubmit.onclick =  function(){
    modalPreset.style.display = "none";
    const presetNameInput = document.getElementById('presetName')
    console.log(presetNameInput.value)
    savePresetUrl = savePresetUrl + "name=" + presetNameInput.value
    savePreset(savePresetUrl)
    presetNameInput.value = ""      
    savePresetUrl = "src/jobSubmitter/saveUserPreset.php?"
}











function savePreset(savePresetUrl){
  let ajax = new XMLHttpRequest();
  ajax.open("GET",savePresetUrl,true);
  ajax.send();
  ajax.onload = function ()
  {
    if(this.readyState == 4 && this.status == 200)
    {
      console.log(this.responseText)
    }
  }
}

removeJobButton.addEventListener("click", function(){
  const jobIdRemove = contextJob.getAttribute('data-job-id')
 // shiftJobsLocalStorage(jobIdRemove, 'left');
  
  contextJob.remove()
  updateOrder('left')
})






////
//// LOCAL STORAGE HANDLING
////

function restoreJobContainer() {
  const jobsData = JSON.parse(localStorage.getItem("jobs")) || [];
  jobsData.sort((a, b) => {
    const idA = parseInt(a.jobId.slice(4)); // extract the number from the jobId string of object a
    const idB = parseInt(b.jobId.slice(4)); // extract the number from the jobId string of object b
    return idA - idB; // compare the numbers and return the comparison result
  });

  jobsData.forEach((jobData) => {
    addJobDiv(
      document.querySelector(`.sourceBox .boxVideo [value="${jobData.videoFile}"]`)?.parentElement ?? null,
      document.querySelector(`.sourceBox .boxAudio [value="${jobData.audioFile}"]`)?.parentElement ?? null,
      jobData.codecValue,
      jobData.contValue,
      jobData.presetValue,
      jobData.resolutionWidth,
      jobData.resolutionHeight,
      jobData.bitrateValue,
      jobData.frameRateValue,
      jobData.isCheckedAudio,
      jobData.audioBitrateValue,
      jobData.isCheckedMail,
      null,
      jobData.jobId,
      jobData.outputName,
    );
  });
}


function saveAddJobDiv(jobDiv) {
  console.log(jobDiv)
  const data = {
    jobId: jobDiv.dataset.jobId,
    videoFile: jobDiv.querySelector('.nonDraggableVideo [name="videoFile[]"]')?.value ?? null,
    audioFile: jobDiv.querySelector('.nonDraggableAudio [name="audioFile[]"]')?.value ?? null,
    codecValue: jobDiv.querySelector("[class=codecDropDown]").value,
    contValue: jobDiv.querySelector("[class=contDropDown]").value,
    presetValue: jobDiv.querySelector("[class=presetDropDown]").value,
    resolutionWidth: jobDiv.querySelector("[class=resolutionWidthInput]").value,
    resolutionHeight: jobDiv.querySelector("[class=resolutionHeightInput]").value,
    bitrateValue: jobDiv.querySelector("[class=bitrateInput]").value,
    frameRateValue: jobDiv.querySelector("[class=framerateDropDown]").value,
    isCheckedAudio: jobDiv.querySelector("[class=audioCheckbox]").checked,
    audioBitrateValue: jobDiv.querySelector("[class=audioBitrateDropDown]").value,
    isCheckedMail: jobDiv.querySelector("[class=sendMailCheckbox]").checked,
    isCheckedMail: jobDiv.querySelector("[class=sendMailCheckbox]").checked,
    outputName: jobDiv.querySelector('[class=outputNameInput]').value,
  };
  let jobs = JSON.parse(localStorage.getItem('jobs')) || []; // Get the current jobs data from localStorage or create an empty array if there is no data yet
  jobs.push(data); // Add the new job to the array of jobs
  localStorage.setItem('jobs', JSON.stringify(jobs)); // Save the updated jobs data back to localStorage
}

function updateJobLocalStorage(jobId) {
  // Find jobDiv from Id
  const jobDiv = document.querySelector(`[data-job-id="${jobId}"]`);

  // Create an object with the updated data from the DOM
  const updatedData = {
    jobId: jobId,
    videoFile: jobDiv.querySelector('.nonDraggableVideo [name="videoFile[]"]')?.value ?? null,
    audioFile: jobDiv.querySelector('.nonDraggableAudio [name="audioFile[]"]')?.value ?? null,
    codecValue: jobDiv.querySelector("[class=codecDropDown]").value,
    contValue: jobDiv.querySelector("[class=contDropDown]").value,
    presetValue: jobDiv.querySelector("[class=presetDropDown]").value,
    resolutionWidth: jobDiv.querySelector("[class=resolutionWidthInput]").value,
    resolutionHeight: jobDiv.querySelector("[class=resolutionHeightInput]").value,
    bitrateValue: jobDiv.querySelector("[class=bitrateInput]").value,
    frameRateValue: jobDiv.querySelector("[class=framerateDropDown]").value,
    isCheckedAudio: jobDiv.querySelector("[class=audioCheckbox]").checked,
    audioBitrateValue: jobDiv.querySelector("[class=audioBitrateDropDown]").value,
    isCheckedMail: jobDiv.querySelector("[class=sendMailCheckbox]").checked,
    outputName: jobDiv.querySelector('[class=outputNameInput]').value,
  };

  // Find the index of the job with the same jobId in the jobs array
  const jobs = JSON.parse(localStorage.getItem('jobs')) || [];
  const index = jobs.findIndex(job => job.jobId === jobId);

  // If a job with the same jobId exists in the jobs array, update its data
  if (index !== -1) {
    jobs[index] = { ...jobs[index], ...updatedData };
    localStorage.setItem('jobs', JSON.stringify(jobs));
  }
}





function shiftJobsLocalStorage(jobId, direction) {
  const jobs = JSON.parse(localStorage.getItem('jobs')) || [];
  
  if(jobs.length==1 && direction == 'left'){
    localStorage.removeItem('jobs')
    return
  }else if(jobs.length==1 && direction == 'right'){
    return
  }
  jobs.sort((a, b) => {
    const idA = parseInt(a.jobId.slice(4)); // extract the number from the jobId string of object a
    const idB = parseInt(b.jobId.slice(4)); // extract the number from the jobId string of object b
    return idA - idB; // compare the numbers and return the comparison result
  });

  let removed = false;
  for (let i = 0; i < jobs.length; i++) {
    if (direction === "left" && !removed && parseInt(jobs[i].jobId.slice(4)) === parseInt(jobId.slice(4))) {
      jobs.splice(i, 1);
      console.log("removed " + jobId);
      removed = true;
    }
    if (typeof jobs[i] != "undefined" && direction === "right" && parseInt(jobs[i].jobId.slice(4)) >= parseInt(jobId.slice(4))) {
      const job = jobs[i];
      jobs.splice(i, 1);
      job.jobId = `job-${parseInt(job.jobId.slice(4)) +  1}`;
      jobs.splice(i, 0, job);
    }
    if (typeof jobs[i] != "undefined" && direction === "left"  && parseInt(jobs[i].jobId.slice(4)) > parseInt(jobId.slice(4))) {
      console.log("shiftleft " + jobs[i].jobId);
      const job = jobs[i];
      jobs.splice(i, 1);
      job.jobId = `job-${parseInt(job.jobId.slice(4)) -  1}`;
      jobs.splice(i, 0, job);
    }
  }

  localStorage.setItem("jobs", JSON.stringify(jobs));
}

function updateOrder(direction){
  const jobContainer = document.getElementById("jobContainer")
  const jobs = jobContainer.querySelectorAll('.job');
  if (jobs.length == 0 && direction == "left"){
    shiftJobsLocalStorage("job-1", 'left'); 
  }
  let isFirstIteration = true;
    jobs.forEach((job, index) => {
      if(isFirstIteration && job.dataset.jobId != `job-${index+1}`){
          const jobShiftId = `job-${index+1}`;
          console.log(jobShiftId);
          shiftJobsLocalStorage(jobShiftId, direction);     
          isFirstIteration = false;
      };
      if(isFirstIteration && job == jobContainer.lastChild){
        const jobShiftId = `job-${index+2}`;
        console.log(jobShiftId);
        shiftJobsLocalStorage(jobShiftId, 'left'); 
        isFirstIteration = false;
      }
        job.dataset.jobId = `job-${index+1}`
    })
}









window.addEventListener("click", function(){
  document.getElementById("context-menu").classList.remove("active")
})

function validateJobSubmission(event){
  var condition = true
  let videoFiles = document.querySelectorAll("[class=containerVideo]")
  let audioFiles = document.querySelectorAll("[class=containerAudio]")
  let names = document.querySelectorAll("[class=outputNameInput]")
  let audioCheckboxes = document.querySelectorAll("[class=audioCheckbox]")
  let sendMailCheckboxes = document.querySelectorAll("[class=sendMailCheckbox]")
  names.forEach(name => {
    if(!isValidFileName(name.value)){
      alert(name.value + " isn't a valid file name!")
      condition = false
    }
  })
  

  videoFiles.forEach(videoFile => {
    if (!videoFile.firstChild){
      alert("Video Source cannot be empty!")
      condition = false
    }
  })

  if(!condition){
    event.preventDefault();
    return false
  }

  sendMailCheckboxes.forEach(checkbox => {
    if(checkbox.checked == true){
      checkbox.innerHTML = checkbox.innerHTML + `<input type="hidden" name="mail[]" value="True"></input>`
    }else{
      checkbox.innerHTML = checkbox.innerHTML + `<input type="hidden" name="mail[]" value=""></input>`
    }
  })

  audioCheckboxes.forEach(checkbox => {
    if(checkbox.checked == true){
      checkbox.innerHTML = checkbox.innerHTML + `<input type="hidden" name="audio[]" value="True"></input>`
    }else{
      checkbox.innerHTML = checkbox.innerHTML + `<input type="hidden" name="audio[]" value=""></input>`
    }
  })

  videoFiles.forEach(videoFile => {
    if (!videoFile.firstChild){
      videoFile.innerHTML =  `<input type="hidden" name="videoFile[]" value=""></input>`
    }
  })
  
  audioFiles.forEach(audioFile => {
    if (!audioFile.firstChild){
      audioFile.innerHTML =  `<input type="hidden" name="audioFile[]" value=""></input>`
    }
  })
  
  localStorage.removeItem('jobs')
  localStorage.removeItem('files')

}


var isValidFileName=(function(){
  var rg1=/^[^\\/:\*\?"<>\|]+$/; // forbidden characters \ / : * ? " < > |
  var rg2=/^\./; // cannot start with dot (.)
  var rg3=/^(nul|prn|con|lpt[0-9]|com[0-9])(\.|$)/i; // forbidden file names
  return function isValid(fname){
    return rg1.test(fname)&&!rg2.test(fname)&&!rg3.test(fname);
  }
})();


