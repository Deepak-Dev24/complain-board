let ALL_CALLS = [];
let FILTERED_CALLS = [];
console.log("🔥 funtion.js LOADED - FINAL VERSION");

let PAGE = 1;
const LIMIT = 50;
let HAS_NEXT = true;

// ===== LOAD DATA =====
async function loadCDR(reset = false) {
  if (reset) {
    PAGE = 1;
    ALL_CALLS = [];
    FILTERED_CALLS = [];
    HAS_NEXT = true;
  }

  if (!HAS_NEXT) return;

  const res = await fetch(`api/cdr.php?page=${PAGE}&limit=${LIMIT}`);
  const json = await res.json();

  HAS_NEXT = json.hasNext === true;

  ALL_CALLS.push(...(json.data || []));
  FILTERED_CALLS = [...ALL_CALLS];

  renderTable(FILTERED_CALLS);
  updateMetrics(FILTERED_CALLS);

  const loadMoreBtn = document.getElementById("loadMore");
  if (loadMoreBtn) {
    loadMoreBtn.style.display = HAS_NEXT ? "block" : "none";
  }
}


// ===== FILTERS =====
function applyFilters() {
  const q = document.getElementById("search").value.toLowerCase();
  const selectedDate = document.getElementById("dateFilter").value;

  FILTERED_CALLS = ALL_CALLS.filter(c => {

    const matchSearch =
      String(c.from || "").toLowerCase().includes(q) ||
      String(c.to || "").toLowerCase().includes(q) ||
      String(c.uuid || "").toLowerCase().includes(q);

    const matchDate = selectedDate
      ? formatIST(c.date).split(",")[0].split("/").reverse().join("-") === selectedDate
      : true;

    return matchSearch && matchDate;
  });

  renderTable(FILTERED_CALLS);
  updateMetrics(FILTERED_CALLS);
}
// ===== METRICS =====
function updateMetrics(calls) {
  document.getElementById("totalCalls").innerText = calls.length;

  const answered = calls.filter(c => c.status === "Answered");
  document.getElementById("answeredCalls").innerText = answered.length;

  const totalDuration = answered.reduce(
    (sum, c) => sum + (c.duration || 0), 0
  );

  document.getElementById("avgDuration").innerText =
    answered.length ? Math.round(totalDuration / answered.length) + "s" : "0s";

  document.getElementById("answerRate").innerText =
    calls.length
      ? Math.round((answered.length / calls.length) * 100) + "%"
      : "0%";
}
// ===== BILLING =====
async function loadBilling() {
  try {
    const res = await fetch("api/billing_summary.php");
    const bill = await res.json();

    const billEl = document.getElementById("totalBill");
    const minutesEl = document.getElementById("totalMinutes");

    if (billEl) {
      billEl.innerText = "₹" + (bill.total_amount || 0);
    }

    if (minutesEl) {
      minutesEl.innerText = bill.total_minutes || 0;
    }

  } catch (e) {
    console.error("Billing load failed", e);
  }
}

// ===== TABLE =====
function renderTable(calls) {
  const tbody = document.getElementById("tableBody");
  tbody.innerHTML = "";

  if (!calls.length) {
    tbody.innerHTML = `
      <tr>
        <td colspan="8" class="text-center text-gray-400 p-10">
          No call records found
        </td>
      </tr>`;
    return;
  }

  calls.forEach(call => {
    tbody.innerHTML += `
      <tr class="border-b">
        <td class="p-2">${formatIST(call.date)}</td>
        <td class="p-2 capitalize">${call.direction}</td>
        <td class="p-2">${call.from}</td>
        <td class="p-2">${call.to}</td>
        <td class="p-2">${call.duration}s</td>
        <td class="p-2 capitalize">${call.status}</td>
        <td class="p-2 ">
              ${
                call.recording_url
                  ? `
                    <audio controls preload="none">
                      <source src="api/stream_recording.php?url=${encodeURIComponent(call.recording_url)}">
                    </audio>
                  `
                  : "—"
              }
        </td>

      </tr>
    `;
  });
}

function formatIST(dateStr) {
  if (!dateStr) return "-";

  // Force UTC by appending Z
  const utcDate = new Date(dateStr + "Z");

  return utcDate.toLocaleString("en-IN", {
    timeZone: "Asia/Kolkata",
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
    hour12: true
  });
}

// ===== SEARCH & DATE FILTER EVENTS =====
document.getElementById("search").addEventListener("input", applyFilters);
document.getElementById("dateFilter").addEventListener("change", applyFilters);

// ===== EXPORT CSV =====
function exportCSV() {
  if (!FILTERED_CALLS.length) return alert("No data to export");

  const headers = [
    "Date","Direction","From","To","Duration","Status","Recording"
  ];

  const rows = FILTERED_CALLS.map(c => [
    formatIST(c.date),
    c.direction,
    c.from,
    c.to,
    c.duration,
    c.status,
    c.recording_url ? "YES" : "NO"
  ].map(v => `"${String(v).replace(/"/g, '""')}"`));

  let csv = headers.join(",") + "\n";
  rows.forEach(r => csv += r.join(",") + "\n");

  const blob = new Blob([csv], { type: "text/csv" });
  const url = URL.createObjectURL(blob);

  const a = document.createElement("a");
  a.href = url;
  a.download = "call_records.csv";
  a.click();

  URL.revokeObjectURL(url);
}



// ===== INIT =====

loadCDR();
loadBilling();

function loadMore() {
  PAGE++;
  loadCDR();
}
