window.TitanRuntime=window.TitanRuntime||{};
window.TitanRuntime.dashboard=function(){
return{
prompt:'',
messages:[],
canvas:{title:'Ready',body:'Awaiting'},
memory:{},
automation:[],
assistants:[{label:'Work AI',status:'idle'},{label:'Memory AI',status:'idle'},{label:'Signal AI',status:'idle'}],
async sendPrompt(){
if(!this.prompt)return;
const input=this.prompt;this.prompt='';
const res=await window.TitanRuntimeAPI.dispatch(input);
this.messages.unshift({role:'You',body:input});
this.messages.unshift({role:res.assistant?.label||'Titan',body:res.result?.body||'Done'});
this.canvas=res.canvas;
this.memory=res.memory;
this.automation=res.automation?.actions||[];
this.assistants.forEach(a=>a.status=(a.label===res.assistant?.label)?'active':'idle');
}}};
