import type { Bud } from "@roots/bud";

/**
 * Bud config
 */
export default async (bud: Bud) => {
  bud
    .proxy(`http://projectname.test`)
    .serve(`http://localhost:3000`)
    .watch([bud.path(`resources/views`), bud.path(`app`)])

    .entry(`app`, [`@scripts/app`, `@styles/app`])
    .entry(`editor`, [`@scripts/editor`, `@styles/editor`])
    .copyDir(`images`)

    .setPublicPath(`/dist/`)
    .experiments(`topLevelAwait`, true);

  await bud.tapAsync(sourceThemeValues);

  bud
    .when(`eslint` in bud, ({ eslint }) =>
      eslint
        .extends([
          `@roots/eslint-config/sage`,
          `@roots/eslint-config/typescript`,
          `plugin:react/jsx-runtime`,
        ])
        .setFix(true)
        .setFailOnWarning(bud.isProduction)
    )

    /**
     * Stylelint config
     */
    .when(`stylelint` in bud, ({ stylelint }) =>
      stylelint
        .extends([
          `@roots/sage/stylelint-config`,
        ])
        .setFix(true)
        .setFailOnWarning(bud.isProduction)
    )

    /**
     * Image minification config
     */
    .when(`imagemin` in bud, ({ imagemin }) =>
      imagemin.encode(`jpeg`, { mozjpeg: true, quality: 70 })
    );
};

/**
 * Find all `*.theme.js` files and apply them to the `theme.json` output
 */
const sourceThemeValues = async ({ error, glob, wpjson }: Bud) => {
  const importMatching = async (paths: Array<string>) =>
    await Promise.all(paths.map(async (path) => (await import(path)).default));

  const setThemeValues = (records: Record<string, unknown>) =>
    Object.entries(records).map((params) => wpjson.set(...params));

  await glob(`resources/**/*.theme.js`)
    .then(importMatching)
    .then((modules) => modules.map(setThemeValues))
    .catch(error);
};
