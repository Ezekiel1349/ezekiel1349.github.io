<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Mi IP pública (limpio)</title>
<style>
  body{font-family:system-ui,Arial;padding:18px}
  .ip{margin:8px 0;padding:10px;background:#f4f4f4;border-radius:6px;display:flex;justify-content:space-between;align-items:center}
  .copy-btn{cursor:pointer;border:none;background:none;font-size:16px}
</style>
</head>
<body>
  <h3>Mi IP pública</h3>
  <div class="ip">
    <span><b>IPv4:</b> <span id="ipv4">—</span></span>
    <button class="copy-btn" onclick="copyIP('ipv4')">📋</button>
  </div>
  <div class="ip">
    <span><b>IPv6:</b> <span id="ipv6">—</span></span>
    <button class="copy-btn" onclick="copyIP('ipv6')">📋</button>
  </div>

<script>
function copyIP(id){
  const ip = document.getElementById(id).innerText;
  if(ip && ip !== '—'){
    navigator.clipboard.writeText(ip).then(()=>{
      // alert("Copiado: " + ip);
    }).catch(err=>{
      alert("Error al copiar: " + err);
    });
  }
}

(async function(){
  const stun = {iceServers:[{urls:'stun:stun.l.google.com:19302'}]};
  let ipv4 = null, ipv6 = null;
  try{
    const pc = new RTCPeerConnection(stun);
    pc.createDataChannel('dot');

    pc.onicecandidate = e => {
      if(!e.candidate || !e.candidate.candidate) return;
      const cand = e.candidate.candidate;
      if(cand.indexOf('typ srflx') === -1) return;
      const parts = cand.split(/\s+/);
      const ip = parts[4] || null;
      if(!ip) return;
      if(ip.indexOf(':') !== -1) ipv6 = ip;
      else if(ip.indexOf('.') !== -1) ipv4 = ip;
      if(ipv4) document.getElementById('ipv4').innerText = ipv4;
      if(ipv6) document.getElementById('ipv6').innerText = ipv6;
    };

    const offer = await pc.createOffer();
    await pc.setLocalDescription(offer);
    setTimeout(()=>{ try{ pc.close(); }catch(e){} }, 1500);
  }catch(err){
    document.getElementById('ipv4').innerText = 'no soportado';
    document.getElementById('ipv6').innerText = 'no soportado';
  }
})();
</script>
</body>
</html>
