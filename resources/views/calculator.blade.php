<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Calculator</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #1e1e2f, #2b2b45);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }

  .calculator {
    background: #202033;
    border-radius: 24px;
    padding: 24px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    width: 320px;
  }

  .top-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
  }

  .last-calc {
    color: #7a7a95;
    font-size: 13px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: pointer;
  }

  .last-calc:not(:empty):hover { color: #a7a7c5; }

  .history-toggle {
    background: none;
    border: none;
    color: #7a7a95;
    font-size: 12px;
    cursor: pointer;
    padding: 4px 6px;
    white-space: nowrap;
  }

  .history-toggle:hover { color: #a7a7c5; }

  .display {
    background: #181826;
    border-radius: 16px;
    padding: 20px 18px;
    margin-bottom: 16px;
    text-align: right;
    overflow: hidden;
  }

  .expression {
    color: #7a7a95;
    font-size: 16px;
    min-height: 20px;
    white-space: nowrap;
    overflow-x: auto;
  }

  .current {
    color: #fff;
    font-size: 40px;
    font-weight: 600;
    white-space: nowrap;
    overflow-x: auto;
    font-variant-numeric: tabular-nums;
  }

  .buttons {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
  }

  button {
    border: none;
    outline: none;
    padding: 18px 0;
    border-radius: 14px;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    background: #2b2b45;
    color: #eee;
    transition: transform 0.1s ease, filter 0.1s ease;
  }

  button:active { transform: scale(0.94); }
  button:hover { filter: brightness(1.15); }

  .btn-op { background: #3a3a5c; color: #9fa8ff; }
  .btn-func { background: #35354f; color: #ff8a8a; }
  .btn-equals { background: #4caf50; color: white; grid-row: span 2; height: 100%; }
  .btn-zero { grid-column: span 2; }

  .save-status {
    font-size: 11px;
    color: #7a7a95;
    text-align: center;
    min-height: 14px;
    margin-top: 8px;
  }

  .save-status.error { color: #f44336; }

  .hidden { display: none !important; }

  .overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
  }

  .modal {
    background: #202033;
    border-radius: 20px;
    padding: 28px;
    width: 320px;
    max-height: 80vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 20px 50px rgba(0,0,0,0.5);
  }

  .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
  }

  .modal-header h2 { color: #eee; font-size: 17px; font-weight: 500; }
  .modal-header button { background: #3a3a55; color: #ddd; padding: 6px 12px; font-size: 13px; }

  .history-list { overflow-y: auto; flex: 1; }
  .history-list::-webkit-scrollbar { width: 6px; }
  .history-list::-webkit-scrollbar-thumb { background: #444460; border-radius: 3px; }

  .history-item {
    background: #181826;
    border-radius: 10px;
    padding: 10px 14px;
    margin-bottom: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
  }

  .history-item-text { min-width: 0; }

  .history-expr {
    color: #7a7a95;
    font-size: 12px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .history-result {
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    font-variant-numeric: tabular-nums;
  }

  .history-date { color: #666680; font-size: 10px; margin-top: 2px; }

  .delete-btn {
    background: none;
    color: #f44336;
    padding: 2px 6px;
    font-size: 12px;
    font-weight: 400;
    flex-shrink: 0;
  }

  .empty-history { color: #8888aa; font-size: 14px; text-align: center; padding: 30px 0; }

  .clear-all {
    margin-top: 10px;
    background: #3a2a2a;
    color: #f44336;
    font-size: 13px;
    padding: 10px;
  }
</style>
</head>
<body>

<div class="calculator">
  <div class="top-row">
    <div class="last-calc" id="lastCalc"></div>
    <button class="history-toggle" id="historyBtn">History ▾</button>
  </div>

  <div class="display">
    <div class="expression" id="expression">&nbsp;</div>
    <div class="current" id="current">0</div>
  </div>

  <div class="buttons">
    <button class="btn-func" data-action="clear">AC</button>
    <button class="btn-func" data-action="backspace">⌫</button>
    <button class="btn-func" data-action="percent">%</button>
    <button class="btn-op" data-action="operator" data-value="÷">÷</button>

    <button data-action="digit" data-value="7">7</button>
    <button data-action="digit" data-value="8">8</button>
    <button data-action="digit" data-value="9">9</button>
    <button class="btn-op" data-action="operator" data-value="×">×</button>

    <button data-action="digit" data-value="4">4</button>
    <button data-action="digit" data-value="5">5</button>
    <button data-action="digit" data-value="6">6</button>
    <button class="btn-op" data-action="operator" data-value="-">−</button>

    <button data-action="digit" data-value="1">1</button>
    <button data-action="digit" data-value="2">2</button>
    <button data-action="digit" data-value="3">3</button>
    <button class="btn-op" data-action="operator" data-value="+">+</button>

    <button class="btn-zero" data-action="digit" data-value="0">0</button>
    <button data-action="decimal">.</button>
    <button class="btn-equals" data-action="equals">=</button>
  </div>

  <div class="save-status" id="saveStatus"></div>
</div>

<div class="overlay hidden" id="historyOverlay">
  <div class="modal">
    <div class="modal-header">
      <h2>Calculation History</h2>
      <button id="closeHistory">Close</button>
    </div>
    <div class="history-list" id="historyList"></div>
    <button class="clear-all hidden" id="clearAll">Clear All History</button>
  </div>
</div>

<script>
  // Laravel API base — routes/api.php is auto-prefixed with /api
  const API_BASE = '/api/calculations';

  const expressionEl = document.getElementById('expression');
  const currentEl = document.getElementById('current');
  const lastCalcEl = document.getElementById('lastCalc');
  const saveStatusEl = document.getElementById('saveStatus');
  const buttonsEl = document.querySelector('.buttons');
  const historyBtn = document.getElementById('historyBtn');
  const historyOverlay = document.getElementById('historyOverlay');
  const closeHistoryBtn = document.getElementById('closeHistory');
  const historyList = document.getElementById('historyList');
  const clearAllBtn = document.getElementById('clearAll');

  let expression = '';
  let display = '0';
  let justEvaluated = false;

  function updateDisplay() {
    currentEl.textContent = display;
    expressionEl.textContent = expression
      ? expression.replace(/[+\-×÷]/g, m => ' ' + m + ' ')
      : '\u00A0';
  }

  function inputDigit(d) {
    if (justEvaluated) { expression = ''; display = '0'; justEvaluated = false; }
    display = display === '0' ? d : display + d;
  }

  function inputDecimal() {
    if (justEvaluated) { expression = ''; display = '0'; justEvaluated = false; }
    if (!display.includes('.')) display += '.';
  }

  function inputOperator(op) {
    justEvaluated = false;
    if (/[+\-×÷]$/.test(expression) && display === '0' && expression.length) {
      expression = expression.slice(0, -1) + op;
    } else {
      expression += display + op;
      display = '0';
    }
  }

  function inputPercent() {
    const val = parseFloat(display);
    if (!isNaN(val)) display = String(val / 100);
  }

  function backspace() {
    if (justEvaluated) return;
    display = display.length > 1 ? display.slice(0, -1) : '0';
  }

  function clearAll() {
    expression = '';
    display = '0';
    justEvaluated = false;
  }

  function safeEval(str) {
    // Only digits, decimal points, and the four operators ever reach the
    // Function constructor, so arbitrary code can't slip through.
    if (!/^[0-9.+\-×÷]+$/.test(str)) throw new Error('Invalid expression');
    const jsExpr = str.replace(/×/g, '*').replace(/÷/g, '/');
    const result = Function('"use strict"; return (' + jsExpr + ')')();
    if (!isFinite(result)) throw new Error('Cannot divide by zero');
    return result;
  }

  function equals() {
    const fullExpr = expression + display;
    if (!expression) return;

    try {
      const result = safeEval(fullExpr);
      const rounded = Math.round(result * 1e10) / 1e10;
      saveCalculation(fullExpr, rounded);
      expression = fullExpr + '=';
      display = String(rounded);
      justEvaluated = true;
    } catch (e) {
      display = 'Error';
      expression = '';
      justEvaluated = true;
    }
  }

  function handleAction(action, value) {
    switch (action) {
      case 'digit': inputDigit(value); break;
      case 'decimal': inputDecimal(); break;
      case 'operator': inputOperator(value); break;
      case 'percent': inputPercent(); break;
      case 'backspace': backspace(); break;
      case 'clear': clearAll(); break;
      case 'equals': equals(); break;
    }
    updateDisplay();
  }

  buttonsEl.addEventListener('click', (e) => {
    const btn = e.target.closest('button');
    if (!btn) return;
    handleAction(btn.dataset.action, btn.dataset.value);
  });

  document.addEventListener('keydown', (e) => {
    if (e.key >= '0' && e.key <= '9') return handleAction('digit', e.key);
    if (e.key === '.') return handleAction('decimal');
    if (e.key === '+') return handleAction('operator', '+');
    if (e.key === '-') return handleAction('operator', '-');
    if (e.key === '*') return handleAction('operator', '×');
    if (e.key === '/') { e.preventDefault(); return handleAction('operator', '÷'); }
    if (e.key === '%') return handleAction('percent');
    if (e.key === 'Enter' || e.key === '=') return handleAction('equals');
    if (e.key === 'Backspace') return handleAction('backspace');
    if (e.key === 'Escape') return handleAction('clear');
  });

  // --- API calls to Laravel ---

  function readableExpr(expr) {
    return expr.replace(/[+\-×÷]/g, m => ' ' + m + ' ');
  }

  async function saveCalculation(expr, result) {
    const expressionText = readableExpr(expr);
    saveStatusEl.textContent = 'Saving...';
    saveStatusEl.className = 'save-status';

    try {
      const res = await fetch(API_BASE, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ expression: expressionText, result: String(result) })
      });
      const data = await res.json();
      if (!res.ok) throw new Error(data.message || 'Unknown error');

      saveStatusEl.textContent = '';
      lastCalcEl.textContent = `Last: ${expressionText} = ${result}`;
      lastCalcEl.dataset.result = result;
    } catch (err) {
      saveStatusEl.textContent = 'Save failed: ' + err.message;
      saveStatusEl.className = 'save-status error';
    }
  }

  async function fetchHistory() {
    const res = await fetch(API_BASE, {
      headers: { 'Accept': 'application/json' }
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.message || 'Unknown error');
    return data;
  }

  async function loadLastCalculation() {
    try {
      const history = await fetchHistory();
      if (history.length > 0) {
        const latest = history[0];
        lastCalcEl.textContent = `Last: ${latest.expression} = ${latest.result}`;
        lastCalcEl.dataset.result = latest.result;
      }
    } catch (err) {
      lastCalcEl.textContent = '';
    }
  }

  async function deleteEntry(id) {
    await fetch(`${API_BASE}/${id}`, {
      method: 'DELETE',
      headers: { 'Accept': 'application/json' }
    });
    renderHistory();
  }

  async function clearAllHistory() {
    if (!confirm('Delete all calculation history? This cannot be undone.')) return;
    await fetch(API_BASE, {
      method: 'DELETE',
      headers: { 'Accept': 'application/json' }
    });
    lastCalcEl.textContent = '';
    renderHistory();
  }

  async function renderHistory() {
    historyList.innerHTML = '<div class="empty-history">Loading...</div>';
    let history;
    try {
      history = await fetchHistory();
    } catch (err) {
      historyList.innerHTML = `<div class="empty-history">Could not load history: ${err.message}</div>`;
      clearAllBtn.classList.add('hidden');
      return;
    }

    historyList.innerHTML = '';

    if (history.length === 0) {
      historyList.innerHTML = '<div class="empty-history">No calculations yet.</div>';
      clearAllBtn.classList.add('hidden');
      return;
    }

    clearAllBtn.classList.remove('hidden');

    history.forEach(entry => {
      const item = document.createElement('div');
      item.className = 'history-item';
      item.innerHTML = `
        <div class="history-item-text">
          <div class="history-expr"></div>
          <div class="history-result"></div>
          <div class="history-date">${new Date(entry.created_at).toLocaleString()}</div>
        </div>
        <button class="delete-btn" data-id="${entry.id}">✕</button>
      `;
      // set text via textContent to avoid HTML injection
      item.querySelector('.history-expr').textContent = entry.expression + ' =';
      item.querySelector('.history-result').textContent = entry.result;
      historyList.appendChild(item);
    });
  }

  // Clicking "Last: ..." drops that result back into the display
  lastCalcEl.addEventListener('click', () => {
    if (!lastCalcEl.dataset.result) return;
    expression = '';
    display = lastCalcEl.dataset.result;
    justEvaluated = true;
    updateDisplay();
  });

  historyBtn.addEventListener('click', () => {
    historyOverlay.classList.remove('hidden');
    renderHistory();
  });

  closeHistoryBtn.addEventListener('click', () => {
    historyOverlay.classList.add('hidden');
  });

  historyOverlay.addEventListener('click', (e) => {
    if (e.target === historyOverlay) historyOverlay.classList.add('hidden');
  });

  historyList.addEventListener('click', (e) => {
    if (e.target.classList.contains('delete-btn')) {
      deleteEntry(Number(e.target.dataset.id));
    }
  });

  clearAllBtn.addEventListener('click', clearAllHistory);

  // On load, pull the most recent calculation from MySQL via Laravel
  loadLastCalculation();
  updateDisplay();
</script>

</body>
</html>
