(() => {
  const berlin = { timeZone: "Europe/Berlin" };
  const POLL_MS = 2000;
  const datetimeEl = document.getElementById("datetime");
  const saveBtn = document.getElementById("saveBtn");
  const saveMsg = document.getElementById("saveMsg");
  const fetchBtn = document.getElementById("fetchBtn");

  if (!datetimeEl || !saveBtn) return;

  let lastUpdatedAt = null;
  let pollInFlight = false;

  const formatPrice = (value) => Number(value).toFixed(3).replace(".", ",");

  const tickClock = () => {
    const parts = new Intl.DateTimeFormat("de-DE", {
      ...berlin,
      day: "2-digit",
      month: "2-digit",
      year: "numeric",
      hour: "2-digit",
      minute: "2-digit",
      second: "2-digit",
      hour12: false,
    }).formatToParts(new Date());

    const get = (type) => parts.find((p) => p.type === type)?.value ?? "";
    const date = `${get("day")}.${get("month")}.${get("year")}`;
    const time = `${get("hour")}:${get("minute")}:${get("second")}`;
    datetimeEl.textContent = `${date}          ${time}`;
  };

  const setInput = (name, value) => {
    const el = document.querySelector(`.value-input[name="${name}"]`);
    if (el) el.value = formatPrice(value);
  };

  const applyPrices = (data) => {
    setInput("e5", data.e5);
    setInput("e10", data.e10);
    setInput("diesel", data.diesel);
    if (data.updatedAt) lastUpdatedAt = data.updatedAt;
  };

  const readInput = (name) => {
    const el = document.querySelector(`.value-input[name="${name}"]`);
    return el ? el.value.trim() : "";
  };

  const isEditingPrice = () => {
    const active = document.activeElement;
    return Boolean(active && active.classList?.contains("value-input"));
  };

  const showMsg = (text, ok) => {
    saveMsg.hidden = false;
    saveMsg.textContent = text;
    saveMsg.classList.toggle("is-ok", ok);
    saveMsg.classList.toggle("is-err", !ok);
    setTimeout(() => {
      saveMsg.hidden = true;
    }, 2500);
  };

  const pollPrices = async () => {
    if (pollInFlight || isEditingPrice()) return;
    pollInFlight = true;
    try {
      const res = await fetch("api/prices.php", { cache: "no-store" });
      if (!res.ok) return;
      const data = await res.json();
      if (!data || data.e5 == null || data.e10 == null || data.diesel == null) return;
      if (data.updatedAt && data.updatedAt === lastUpdatedAt) return;
      if (isEditingPrice()) return;
      applyPrices(data);
    } catch (err) {
      console.error(err);
    } finally {
      pollInFlight = false;
    }
  };

  saveBtn.addEventListener("click", async () => {
    saveBtn.disabled = true;
    try {
      const res = await fetch("api/save.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          e5: readInput("e5"),
          e10: readInput("e10"),
          diesel: readInput("diesel"),
        }),
      });
      const data = await res.json();
      if (!res.ok || !data.ok) throw new Error(data.error || "Fehler");
      if (data.updatedAt) lastUpdatedAt = data.updatedAt;
      showMsg("Gespeichert", true);
    } catch (err) {
      showMsg("Speichern fehlgeschlagen", false);
      console.error(err);
    } finally {
      saveBtn.disabled = false;
    }
  });

  if (fetchBtn) {
    fetchBtn.addEventListener("click", async () => {
      fetchBtn.disabled = true;
      try {
        const res = await fetch("api/fetch.php", { cache: "no-store" });
        const data = await res.json();
        if (!res.ok || !data.ok) throw new Error(data.error || "Fehler");
        applyPrices(data);
        showMsg("Aktuelle Preise geladen", true);
      } catch (err) {
        showMsg("Abruf fehlgeschlagen", false);
        console.error(err);
      } finally {
        fetchBtn.disabled = false;
      }
    });
  }

  tickClock();
  setInterval(tickClock, 1000);
  pollPrices();
  setInterval(pollPrices, POLL_MS);
})();
