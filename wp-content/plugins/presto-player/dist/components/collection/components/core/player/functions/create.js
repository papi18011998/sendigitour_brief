import PrestoPlayer from './presto-player';
import { isHLS } from './util';

export default ({ config, selector, src, preload, provider }) => {
  // dynamically load hls module if we have an hls video
  if (src && isHLS(src)) {
    return import('./hls').then(module => {
      const hls = module.default;
      return hls({ config, selector, src, preload });
    });
  }

  return new Promise(resolve => {
    const player = new PrestoPlayer(selector, { ...config });
    return resolve(player);
  });
};
