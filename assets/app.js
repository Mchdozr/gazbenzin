(() => {
  const berlin = { timeZone: "Europe/Berlin" };
  const PRICE_KEYS = ["diesel", "e10", "e5", "superPlus", "adBlue"];
  const datetimeEl = document.getElementById("datetime");
  const saveBtn = document.getElementById("saveBtn");
  const saveMsg = document.getElementById("saveMsg");
  const fetchBtn = document.getElementById("fetchBtn");

  if (!datetimeEl || !saveBtn) return;

  const formatPrice = (value) => Number(value).toFixed(3).replace(".", ",");

  const splitPrice = (formatted) => {
    const raw = String(formatted);
    return {
      full: raw,
      main: raw.slice(0, -1),
      sup: raw.slice(-1),
    };
  };

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

  const syncPriceView = (input) => {
    const card = input.closest(".card");
    if (!card) return;
    const mainEl = card.querySelector(".price-main");
    const supEl = card.querySelector(".price-sup");
    if (!mainEl || !supEl) return;
    const parts = splitPrice(input.value.trim() || "0,000");
    mainEl.textContent = parts.main;
    supEl.textContent = parts.sup;
  };

  const setInput = (name, value) => {
    const el = document.querySelector(`.value-input[name="${name}"]`);
    if (!el) return;
    el.value = formatPrice(value);
    syncPriceView(el);
  };

  const readInput = (name) => {
    const el = document.querySelector(`.value-input[name="${name}"]`);
    return el ? el.value.trim() : "";
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

  document.querySelectorAll(".card").forEach((card) => {
    const input = card.querySelector(".value-input");
    if (!input) return;
    card.querySelector(".price")?.addEventListener("click", () => input.focus());
    input.addEventListener("input", () => syncPriceView(input));
    input.addEventListener("blur", () => syncPriceView(input));
    syncPriceView(input);
  });

  saveBtn.addEventListener("click", async () => {
    saveBtn.disabled = true;
    try {
      const body = {};
      for (const key of PRICE_KEYS) body[key] = readInput(key);
      const res = await fetch("api/save.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(body),
      });
      const data = await res.json();
      if (!res.ok || !data.ok) throw new Error(data.error || "Fehler");
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
        for (const key of PRICE_KEYS) {
          if (data[key] != null) setInput(key, data[key]);
        }
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
})();
