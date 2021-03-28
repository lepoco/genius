// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

namespace Genius.Code.Engine
{
    /// <summary>
    /// A condition which is or is not met by the <see cref="Engine.Product"/>. Not used directly, but with an <see cref="Engine.Reference"/>.
    /// <para>For example, <see cref="Condition"/> can be a symptom of a medical condition like a cough. And <see cref="Engine.Product"/> can be a disease like a Flu.</para>
    /// </summary>
    public sealed class Condition : Parents.ExpertElement
    {
        /// <summary>
        /// Gets or sets the unique identifier of the <see cref="Engine.Condition"/>, which is important because <see cref="Engine.Predictor.Solver"/> uses it when looking for <see cref="Engine.Product"/>.
        /// </summary>
        public int ID { get; set; }

        /// <summary>
        /// Gets or sets the human-readable <see cref="Engine.Condition"/> name.
        /// <para>For example, it could be the simple name for a symptom like "<c>Fever</c>".</para>
        /// </summary>
        public string Name { get; set; }

        /// <summary>
        /// Gets or sets the human-readable <see cref="Engine.Condition"/> description.
        /// <para>Optional extensive description, such as "<c>High body temperature measured with available diagnostic tools</c>".</para>
        /// </summary>
        public string Description { get; set; }
    }
}
