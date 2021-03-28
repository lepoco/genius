// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

namespace Genius.Code
{
    /// <summary>
    /// Global Hook
    /// <para>Makes it easier to refer to global objects used in the application.</para>
    /// </summary>
    internal static class GH
    {
        /// <summary>
        /// Returns the object of the current application.
        /// </summary>
        internal static App App
        {
            get => (App)System.Windows.Application.Current;
        }

        /// <summary>
        /// Returns main application version.
        /// </summary>
        /// <returns>
        /// Formated <see langword="string"/> containing periods and commas, e.g .: <c>1.3.5541.9126</c>
        /// </returns>
        internal static string Version
        {
            get => System.Reflection.Assembly.GetExecutingAssembly().GetName().Version.ToString();
        }

        /// <summary>
        /// Returns main navigation container.
        /// </summary>
        internal static MaterialWPF.Controls.Navigation Navigation
        {
            get => (GH.App.MainWindow as Views.Container).rootNavigation;
        }

        /// <summary>
        /// Main snackbar visible throughout the entire app.
        /// </summary>
        internal static MaterialWPF.Controls.Snackbar GlobalSnackbar
        {
            get => (GH.App.MainWindow as Views.Container).Snackbar;
        }

        /// <summary>
        /// Currently edited or used Expert System.
        /// </summary>
        internal static Code.Engine.System CurrentExpertSystem
        {
            get => GH.App.CurrentExpertSystem;
        }

        /// <summary>
        /// Allows you to navigate the main application <see cref="System.Windows.Controls.Frame"/> using the default <see cref="MaterialWPF.Controls.Navigation"/>.
        /// </summary>
        /// <param name="tag">The tag of the page being opened.</param>
        /// <param name="refresh">If <see langword="true"/>, the page instance will be recreated.</param>
        internal static void Navigate(string tag, bool refresh = false)
        {
            (GH.App.MainWindow as Views.Container).rootNavigation.Navigate(tag, refresh);
        }
    }
}
