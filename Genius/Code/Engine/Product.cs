// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

namespace Genius.Code.Engine
{
    /// <summary>
    /// A result whose components are <see cref="Engine.Condition"/>'s that meet or belong to it.
    /// <para>For example, <see cref="Engine.Product"/> may be Flu that meets a certain amount of <see cref="Engine.Condition"/>'s, like fever or headache.</para>
    /// </summary>
    public sealed class Product : Parents.ExpertElement
    {
        /// <summary>
        /// Gets or sets the unique identifier of the <see cref="Engine.Product"/>, which is important because <see cref="Engine.Predictor.Solver"/> uses it when looking for a matching <see cref="Engine.Condition"/>.
        /// </summary>
        public int ID { get; set; }

        /// <summary>
        /// Gets or sets the human-readable <see cref="Engine.Product"/> name.
        /// <para>For example, it may be a disease such as a "<c>Flu</c>".</para>
        /// </summary>
        public string Name { get; set; }

        /// <summary>
        /// Gets or sets the human-readable <see cref="Engine.Product"/> description.
        /// <para>Optional extensive description, such as "<c>Infectious disease of the respiratory system transmitted by airborne droplets. Caused by Influenza viruses</c>".</para>
        /// </summary>
        public string Description { get; set; }
    }
}
