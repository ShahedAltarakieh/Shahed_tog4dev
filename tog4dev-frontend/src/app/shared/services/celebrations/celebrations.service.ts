import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class CelebrationsService {

  spawnHearts(event: MouseEvent): void {
    const heartCount = 8;
    const golden = '#FECD0F';
    const goldenDark = '#E5B800';
    const colors = [golden, goldenDark, '#FFE066', golden, '#FFC107'];

    for (let i = 0; i < heartCount; i++) {
      const el = document.createElement('span');
      const color = colors[i % colors.length];
      const size = Math.random() * 14 + 20;
      const startX = event.clientX + (Math.random() - 0.5) * 60;
      const startY = event.clientY;
      const delay = i * 80;

      el.innerHTML = '&#x2764;';
      el.style.cssText = `
        position: fixed;
        z-index: 99999;
        font-size: ${size}px;
        color: ${color};
        pointer-events: none;
        left: ${startX}px;
        top: ${startY}px;
        opacity: 0;
        text-shadow: 0 0 8px ${golden}, 0 0 16px ${golden}80;
        filter: drop-shadow(0 0 4px ${golden});
      `;
      document.body.appendChild(el);

      const riseDistance = Math.random() * 200 + 250;
      const sway = (Math.random() - 0.5) * 120;
      const duration = Math.random() * 800 + 1200;
      const peakScale = Math.random() * 0.6 + 1.2;

      setTimeout(() => {
        el.style.opacity = '1';
        el.style.transition = `none`;

        const startTime = performance.now();
        const animate = (now: number) => {
          const elapsed = now - startTime;
          const progress = Math.min(elapsed / duration, 1);

          const easeOut = 1 - Math.pow(1 - progress, 3);
          const y = -riseDistance * easeOut;
          const x = sway * Math.sin(progress * Math.PI * 2);
          const scale = progress < 0.3
            ? 0.3 + (progress / 0.3) * (peakScale - 0.3)
            : peakScale - (progress - 0.3) * 0.4;
          const rotation = sway > 0 ? progress * 30 : progress * -30;
          const opacity = progress > 0.6 ? 1 - ((progress - 0.6) / 0.4) : 1;

          el.style.transform = `translateY(${y}px) translateX(${x}px) scale(${scale}) rotate(${rotation}deg)`;
          el.style.opacity = `${opacity}`;

          if (progress < 1) {
            requestAnimationFrame(animate);
          } else {
            if (el.parentNode) el.parentNode.removeChild(el);
          }
        };
        requestAnimationFrame(animate);
      }, delay);
    }

    this.spawnSparkles(event);
  }

  private spawnSparkles(event: MouseEvent): void {
    const sparkleCount = 12;
    const golden = '#FECD0F';

    for (let i = 0; i < sparkleCount; i++) {
      const el = document.createElement('span');
      el.innerHTML = '&#x2728;';
      const size = Math.random() * 10 + 8;
      const startX = event.clientX + (Math.random() - 0.5) * 40;
      const startY = event.clientY + (Math.random() - 0.5) * 20;

      el.style.cssText = `
        position: fixed;
        z-index: 99998;
        font-size: ${size}px;
        left: ${startX}px;
        top: ${startY}px;
        pointer-events: none;
        opacity: 1;
        color: ${golden};
      `;
      document.body.appendChild(el);

      const angle = (i / sparkleCount) * Math.PI * 2;
      const dist = Math.random() * 60 + 30;
      const dx = Math.cos(angle) * dist;
      const dy = Math.sin(angle) * dist - 40;

      requestAnimationFrame(() => {
        el.style.transition = 'all 0.8s ease-out';
        el.style.transform = `translate(${dx}px, ${dy}px) scale(0.3)`;
        el.style.opacity = '0';
      });

      setTimeout(() => {
        if (el.parentNode) el.parentNode.removeChild(el);
      }, 900);
    }
  }

  launchCelebration(): void {
    const duration = 4000;
    const end = Date.now() + duration;

    const colors = ['#ff0000', '#ff6600', '#ffcc00', '#33cc33', '#3399ff', '#9933ff', '#ff3399', '#FFD700', '#00CED1'];
    const shapes = ['●', '■', '▲', '★', '♦', '✦'];

    const frame = () => {
      if (Date.now() > end) return;

      for (let i = 0; i < 5; i++) {
        const el = document.createElement('span');
        const color = colors[Math.floor(Math.random() * colors.length)];
        const shape = shapes[Math.floor(Math.random() * shapes.length)];
        const startX = Math.random() * window.innerWidth;
        const size = Math.random() * 12 + 8;

        el.textContent = shape;
        el.style.cssText = `
          position: fixed;
          z-index: 99999;
          left: ${startX}px;
          top: -20px;
          font-size: ${size}px;
          color: ${color};
          pointer-events: none;
          opacity: 1;
        `;
        document.body.appendChild(el);

        const drift = (Math.random() - 0.5) * 200;
        const fallDuration = Math.random() * 2000 + 2000;
        const rotation = Math.random() * 720 - 360;

        requestAnimationFrame(() => {
          el.style.transition = `all ${fallDuration}ms ease-in`;
          el.style.transform = `translateY(${window.innerHeight + 40}px) translateX(${drift}px) rotate(${rotation}deg)`;
          el.style.opacity = '0.3';
        });

        setTimeout(() => {
          if (el.parentNode) el.parentNode.removeChild(el);
        }, fallDuration + 100);
      }

      requestAnimationFrame(frame);
    };

    frame();

    this.launchFireworks();
  }

  private launchFireworks(): void {
    const bursts = 6;
    for (let b = 0; b < bursts; b++) {
      setTimeout(() => {
        this.createFireworkBurst(
          Math.random() * window.innerWidth * 0.8 + window.innerWidth * 0.1,
          Math.random() * window.innerHeight * 0.4 + 50
        );
      }, b * 600);
    }
  }

  launchThankYouParty(): void {
    const duration = 5000;
    const hearts = ['❤️', '💛', '💚', '🧡', '💙'];
    const happyFaces = ['😊', '🤗', '😄', '🥳', '👦', '👧', '🧒'];
    const allEmoji = [...hearts, ...hearts, ...happyFaces];
    const particleCount = 30;
    const interval = duration / particleCount;

    for (let i = 0; i < particleCount; i++) {
      setTimeout(() => {
        const el = document.createElement('span');
        const emoji = allEmoji[Math.floor(Math.random() * allEmoji.length)];
        const startX = Math.random() * window.innerWidth * 0.8 + window.innerWidth * 0.1;
        const size = Math.random() * 16 + 20;

        el.textContent = emoji;
        el.style.cssText = `
          position: fixed;
          z-index: 100000;
          left: ${startX}px;
          bottom: -40px;
          font-size: ${size}px;
          pointer-events: none;
          opacity: 1;
        `;
        document.body.appendChild(el);

        const riseHeight = window.innerHeight + 80;
        const sway = (Math.random() - 0.5) * 160;
        const riseDuration = Math.random() * 2000 + 2500;
        const rotation = (Math.random() - 0.5) * 60;

        requestAnimationFrame(() => {
          el.style.transition = `all ${riseDuration}ms ease-out`;
          el.style.transform = `translateY(-${riseHeight}px) translateX(${sway}px) rotate(${rotation}deg)`;
          el.style.opacity = '0';
        });

        setTimeout(() => {
          if (el.parentNode) el.parentNode.removeChild(el);
        }, riseDuration + 100);
      }, i * interval);
    }

    const thankYou = document.createElement('div');
    thankYou.textContent = 'شكراً! Thank You!';
    thankYou.style.cssText = `
      position: fixed;
      z-index: 100001;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) scale(0);
      font-size: 48px;
      font-weight: 800;
      color: #FECD0F;
      text-shadow: 0 2px 12px rgba(0,0,0,0.5), 0 0 30px rgba(254,205,15,0.4);
      pointer-events: none;
      white-space: nowrap;
      font-family: inherit;
    `;
    document.body.appendChild(thankYou);

    requestAnimationFrame(() => {
      thankYou.style.transition = 'transform 0.6s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.6s ease';
      thankYou.style.transform = 'translate(-50%, -50%) scale(1)';
    });

    setTimeout(() => {
      thankYou.style.transition = 'opacity 1s ease-out';
      thankYou.style.opacity = '0';
    }, 3500);

    setTimeout(() => {
      if (thankYou.parentNode) thankYou.parentNode.removeChild(thankYou);
    }, 5000);
  }

  private createFireworkBurst(cx: number, cy: number): void {
    const colors = ['#ff0000', '#FFD700', '#00ff00', '#00CED1', '#ff3399', '#ff6600'];
    const particleCount = 20;
    const color = colors[Math.floor(Math.random() * colors.length)];

    for (let i = 0; i < particleCount; i++) {
      const el = document.createElement('span');
      const angle = (i / particleCount) * Math.PI * 2;
      const distance = Math.random() * 80 + 60;
      const dx = Math.cos(angle) * distance;
      const dy = Math.sin(angle) * distance;

      el.textContent = '●';
      el.style.cssText = `
        position: fixed;
        z-index: 99999;
        left: ${cx}px;
        top: ${cy}px;
        font-size: ${Math.random() * 6 + 4}px;
        color: ${color};
        pointer-events: none;
        opacity: 1;
      `;
      document.body.appendChild(el);

      requestAnimationFrame(() => {
        el.style.transition = 'all 1s ease-out';
        el.style.transform = `translate(${dx}px, ${dy}px)`;
        el.style.opacity = '0';
      });

      setTimeout(() => {
        if (el.parentNode) el.parentNode.removeChild(el);
      }, 1200);
    }
  }
}
